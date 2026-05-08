<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Beeyev\Thumbor\Thumbor;
use Beeyev\Thumbor\Manipulations\Resize;
use Beeyev\Thumbor\Manipulations\Fit;
use Session;

class LivroController extends Controller
{
    private string $apiUrl = 'https://api.dentalgo.com.br';

    public function livros()
    {
        $locale = in_array(session()->get('lang_code'), ['pt', 'en', 'es']) ? session()->get('lang_code') : '';
        $livros = 'livros' . $locale;
        $backupLivros = 'backup_livros' . $locale;
        $token = Cache::get('tokenGlobal');

        // 1. TENTA CACHE PRIMÁRIO
        if (Cache::has($livros)) {
            $cachedValue = Cache::get($livros);
            if ($this->validateCacheLivros($cachedValue)) {
                return $cachedValue;
            }
            Cache::forget($livros);
        }

        // 2. TENTA API COM LOCK
        $lock = Cache::lock('lock_livros_' . $locale, 30);
        if ($lock->get()) {
            try {
                usleep(300000); // Fôlego inicial (300ms)
                $respCourtesies = Http::withToken($token)->timeout(10)->get($this->apiUrl . '/catalog/books/courtesies');
                $conteudoG = $respCourtesies->object();

                if (isset($conteudoG->rows)) {
                    foreach ($conteudoG->rows as $key => $produto) {
                        if (!empty($produto->cover)) {
                            $produto->cover = $this->generateThumborUrl($produto->cover);
                        }
                    }
                }

                usleep(500000); // Pacing entre requisições (500ms)
                $respHome = Http::withToken($token)->timeout(10)->get($this->apiUrl . '/catalog/home?language=');
                $conteudo = $respHome->object();

                if (is_object($conteudo) || is_array($conteudo)) {
                    foreach ($conteudo as $key => $colecoes) {
                        if ($key != 'banners' && isset($colecoes->rows)) {
                            foreach ($colecoes->rows as $produto) {
                                if (!empty($produto->cover)) {
                                    $produto->cover = $this->generateThumborUrl($produto->cover);
                                }
                                if (isset($produto->authors)) {
                                    foreach ($produto->authors as $autor) {
                                        if (!empty($autor->photoURL)) {
                                            $autor->photoURL = $this->generateThumborUrl($autor->photoURL);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $retorno = ['livrosG' => $conteudoG, 'livros' => $conteudo];

                if ($this->validateCacheLivros($retorno) && $respCourtesies->successful() && $respHome->successful()) {
                    Cache::put($livros, $retorno, 864000); // 10 dias
                    Cache::put($backupLivros, $retorno, 2592000); // 30 dias (Backup)
                    $lock->release();
                    return $retorno;
                }
            } catch (\Exception $e) {
                \Log::error('Erro ao buscar livros na API: ' . $e->getMessage());
            } finally {
                $lock->release();
            }
        }

        // 3. FALLBACK: Tenta Backup (API falhou ou Lock ocupado)
        if (Cache::has($backupLivros)) {
            $backupValue = Cache::get($backupLivros);
            if ($this->validateCacheLivros($backupValue)) {
                return $backupValue;
            }
        }

        return ['livrosG' => null, 'livros' => null];
    }

    public function livrosComprados()
    {
        $locale = in_array(session()->get('lang_code'), ['pt', 'en', 'es']) ? session()->get('lang_code') : '';
        $usuario = session()->get('usuario');
        $userId = is_object($usuario) ? ($usuario->id ?? '') : (is_array($usuario) ? ($usuario['id'] ?? '') : '');
        if (empty($userId)) {
            $userId = md5(session()->get('token'));
        }
        
        $livrosKey = 'livrosComprado_' . $userId . '_' . $locale;
        $backupKey = 'backup_livrosComprado_' . $userId . '_' . $locale;
        $token = session()->get('token');

        // 1. TENTA CACHE PRIMÁRIO
        if (Cache::has($livrosKey)) {
            $cachedValue = Cache::get($livrosKey);
            if (is_array($cachedValue)) {
                Log::info('[LIVROS-DEBUG] Retornando do cache primário', [
                    'userId' => $userId,
                    'total_livros_cache' => count($cachedValue)
                ]);
                return $cachedValue;
            }
            Cache::forget($livrosKey);
        }

        // 2. TENTA API
        try {
            Log::info('[LIVROS-DEBUG] === INÍCIO busca livros comprados (API) ===', ['userId' => $userId]);

            usleep(100000); // Fôlego inicial (100ms)

            // --- CHAMADA 1: Livros do plano (subscription/collections) ---
            $respPlano = Http::withToken($token)->timeout(10)->get($this->apiUrl . '/subscription/collections', [
                'page' => 1,
                'q[productType]' => 'book'
            ]);
            $conteudoPlano = $respPlano->object();

            Log::info('[LIVROS-DEBUG] Resposta /subscription/collections', [
                'http_status' => $respPlano->status(),
                'successful' => $respPlano->successful(),
                'has_rows' => isset($conteudoPlano->rows),
                'total_rows' => isset($conteudoPlano->rows) ? count($conteudoPlano->rows) : 0,
                'has_count' => isset($conteudoPlano->count) ? $conteudoPlano->count : 'N/A',
                'has_totalPages' => isset($conteudoPlano->totalPages) ? $conteudoPlano->totalPages : 'N/A',
            ]);

            $produtoComprados = array();

            if ($respPlano->successful() && is_object($conteudoPlano) && isset($conteudoPlano->rows)) {
                foreach ($conteudoPlano->rows as $keyC => $colecao) {
                    if (isset($colecao->products)) {
                        foreach ($colecao->products as $key => $produto) {
                            if (!empty($produto->cover)) {
                                $produto->cover = $this->generateThumborUrl($produto->cover);
                            }
                            
                            $id = $produto->id;
                            if (!collect($produtoComprados)->contains('id', $id)) {
                                $produtoComprados[] = $this->formatProduct($produto, 'plano');
                                Log::info('[LIVROS-DEBUG] [PLANO] Livro adicionado', ['id' => $id, 'title' => $produto->title ?? 'sem titulo']);
                            }
                        }
                    }
                }
            }

            $totalPlano = count($produtoComprados);
            Log::info('[LIVROS-DEBUG] Total livros do PLANO: ' . $totalPlano);

            usleep(250000); // Pacing entre requisições (250ms)

            // --- CHAMADA 2: Livros comprados (catalog/libraries) ---
            $respLib = Http::withToken($token)->timeout(10)->get($this->apiUrl . '/catalog/libraries?page=1');
            $conteudoLib = $respLib->object();

            Log::info('[LIVROS-DEBUG] Resposta /catalog/libraries', [
                'http_status' => $respLib->status(),
                'successful' => $respLib->successful(),
                'has_rows' => isset($conteudoLib->rows),
                'total_rows' => isset($conteudoLib->rows) ? count($conteudoLib->rows) : 0,
                'has_count' => isset($conteudoLib->count) ? $conteudoLib->count : 'N/A',
                'has_totalPages' => isset($conteudoLib->totalPages) ? $conteudoLib->totalPages : 'N/A',
            ]);

            if ($respLib->successful() && is_object($conteudoLib) && isset($conteudoLib->rows)) {
                foreach ($conteudoLib->rows as $produto) {
                    if (!empty($produto->cover)) {
                        $produto->cover = $this->generateThumborUrl($produto->cover);
                    }
                    if (!collect($produtoComprados)->contains('id', $produto->id)) {
                        $produtoComprados[] = $this->formatProduct($produto, 'comprado');
                        Log::info('[LIVROS-DEBUG] [COMPRADO] Livro adicionado', ['id' => $produto->id, 'title' => $produto->title ?? 'sem titulo']);
                    } else {
                        Log::info('[LIVROS-DEBUG] [COMPRADO] Livro DUPLICADO (já no plano)', ['id' => $produto->id, 'title' => $produto->title ?? 'sem titulo']);
                    }
                }
            }

            $totalFinal = count($produtoComprados);
            Log::info('[LIVROS-DEBUG] === FIM === Total final: ' . $totalFinal . ' (Plano: ' . $totalPlano . ' + Comprados: ' . ($totalFinal - $totalPlano) . ')');

            if (!empty($produtoComprados) || ($respPlano->successful() && $respLib->successful())) {
                Cache::put($livrosKey, $produtoComprados, 600); // 10 minutos
                Cache::put($backupKey, $produtoComprados, 2592000); // 30 dias (Backup)
                return $produtoComprados;
            }
        } catch (\Exception $e) {
            Log::error('[LIVROS-DEBUG] EXCEPTION ao buscar livros comprados', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        // 3. FALLBACK: Tenta Backup
        if (Cache::has($backupKey)) {
            Log::warning('[LIVROS-DEBUG] Usando FALLBACK backup cache', ['userId' => $userId]);
            return Cache::get($backupKey);
        }

        Log::warning('[LIVROS-DEBUG] Nenhum livro encontrado (nem API nem backup)', ['userId' => $userId]);
        return [];
    }

    private function validateCacheLivros($value): bool
    {
        if (!is_array($value) || !isset($value['livrosG']) || !isset($value['livros'])) return false;
        
        // Valida se não há erro de autorização nos blocos principais
        if (isset($value['livrosG']->code) && $value['livrosG']->code === 'unauthorized') return false;
        if (isset($value['livros']->code) && $value['livros']->code === 'unauthorized') return false;
        
        return is_object($value['livrosG']);
    }

    private function formatProduct($produto, $tipo): array
    {
        return [
            'id' => $produto->id,
            'createdAt' => $produto->createdAt ?? '',
            'updatedAt' => $produto->updatedAt ?? '',
            'title' => $produto->title ?? '',
            'cover' => $produto->cover ?? '',
            'brief' => $produto->brief ?? '',
            'publishDate' => $produto->publishDate ?? '',
            'price' => $produto->price ?? 0,
            'customerCourtesy' => $produto->customerCourtesy ?? 0,
            'internalCode' => $produto->internalCode ?? '',
            'subscriberCourtesy' => $produto->subscriberCourtesy ?? 0,
            'monetizationForFiliations' => $produto->monetizationForFiliations ?? 0,
            'category' => $produto->category ?? '',
            'length' => $produto->length ?? 0,
            'itemsQuantityPerLanguage' => $produto->itemsQuantityPerLanguage ?? null,
            'status' => $produto->status ?? '',
            'digitalProduct' => $produto->digitalProduct ?? true,
            'availableLanguages' => $produto->availableLanguages ?? [],
            'availableFileFormats' => $produto->availableFileFormats ?? [],
            'productType' => $produto->productType ?? '',
            'collections' => $produto->collections ?? '',
            'tipo' => $tipo
        ];
    }

    private function generateThumborUrl($imageUrl): string
    {
        $thumbor = new Thumbor('https://thumbor.dentalgo.com.br/', '8e965d636dc76246b');
        $thumbor->resize(Resize::ORIG, 450);
        $thumbor->fit(Fit::FIT_IN);
        $thumbor->imageUrl($imageUrl);
        return $thumbor->get();
    }
}
