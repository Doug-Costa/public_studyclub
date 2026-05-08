<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Beeyev\Thumbor\Thumbor;
use Beeyev\Thumbor\Manipulations\Resize;
use Beeyev\Thumbor\Manipulations\Fit;
use Illuminate\Support\Facades\Log;
use Session;

class RevistaController extends Controller
{
    private string $apiUrl = 'https://api.dentalgo.com.br';
    private string $thumborUrl = 'https://thumbor.dentalgo.com.br/';
    private string $thumborKey = '8e965d636dc76246b';

    public function revista($id)
    {
        $locale = in_array(session()->get('lang_code'), ['pt', 'en', 'es']) ? session()->get('lang_code') : '';
        $revistaKey = 'revista' . $id . '' . $locale;
        $backupKey = 'backup_revista' . $id . '' . $locale;
        $token = (string)Cache::get('tokenGlobal');

        // 1. TENTA CACHE PRIMÁRIO
        if (Cache::has($revistaKey)) {
            $cachedValue = Cache::get($revistaKey);
            if ($this->validateCacheRevista($cachedValue)) {
                return $cachedValue;
            }
            Cache::forget($revistaKey);
        }

        // 2. TENTA API COM LOCK (Proteção contra Cache Stampede)
        $lockName = 'lock_revista_' . $id . $locale;
        return Cache::lock($lockName, 30)->get(function () use ($id, $token, $revistaKey, $backupKey) {
            try {
                // Fetch in Parallel
                $responses = Http::pool(fn (\Illuminate\Http\Client\Pool $pool) => [
                    $pool->as('sub')->withToken($token)->timeout(12)->get($this->apiUrl . '/subscription/products/' . $id),
                    $pool->as('cat')->withToken($token)->timeout(12)->get($this->apiUrl . '/catalog/products/' . $id),
                    $pool->as('items')->withToken($token)->timeout(12)->get($this->apiUrl . '/admin/products/' . $id . '/items'),
                ]);

                if ($responses['sub'] instanceof \Throwable || $responses['cat'] instanceof \Throwable || $responses['items'] instanceof \Throwable) {
                    throw new \Exception('Falha de rede nas APIs da revista');
                }

                if ($responses['sub']->status() === 401 || $responses['cat']->status() === 401 || $responses['items']->status() === 401) {
                    session()->flush();
                    return 'deslogou';
                }

                if (!$responses['sub']->successful() || !$responses['cat']->successful() || !$responses['items']->successful()) {
                    throw new \Exception('Falha HTTP na API da revista');
                }

                $conteudo = $responses['sub']->object();
                $conteudo_catalogo = $responses['cat']->object();
                $conteudo_itens = $responses['items']->object();

                // Process Metadata & Covers
                if (is_object($conteudo) && !empty($conteudo->cover)) {
                    $conteudo->cover = $this->generateThumborUrl($conteudo->cover);
                }

                if (is_object($conteudo) && isset($conteudo->productItems)) {
                    foreach ($conteudo->productItems as $key => $value) {
                        if (!empty($value->cover)) {
                            $conteudo->productItems[$key]->cover = $this->generateThumborUrl($value->cover);
                        }
                    }
                }

                // Map items by ID for O(1) lookup in Blade
                $mappedItems = [];
                if (is_array($conteudo_itens)) {
                    foreach ($conteudo_itens as $item) {
                        if (isset($item->id)) {
                            $mappedItems[$item->id] = $item;
                        }
                    }
                }

                $conteudo_final = array($conteudo, $conteudo_catalogo, $mappedItems);

                if ($this->validateCacheRevista($conteudo_final)) {
                    Cache::put($revistaKey, $conteudo_final, 864000); // 10 dias
                    Cache::put($backupKey, $conteudo_final, 2592000); // 30 dias
                    return $conteudo_final;
                }
            } catch (\Exception $e) {
                Log::warning('Fallback Ativado em RevistaController (' . $id . '): Acesso à API falhou. ' . $e->getMessage());
            }

            // Fallback to backup if API failed
            if (Cache::has($backupKey)) {
                return Cache::get($backupKey);
            }

            return [null, null, null];
        }) ?? (Cache::get($backupKey) ?? [null, null, null]);
    }

    private function validateCacheRevista($value): bool
    {
        if (!is_array($value) || count($value) !== 3) return false;
        
        // Verifica se o objeto principal eh valido
        if (!is_object($value[0]) || empty($value[0]->title)) return false;
        
        // So invalida se for explicitamente um erro de autorização
        if (isset($value[0]->code) && $value[0]->code === 'unauthorized') return false;
        
        return true;
    }

    public function produtocomprado($id)
    {
        $token = session()->get('token');
        if (!$token) return [null, null, null];

        $cacheKey = 'prod_comprado_' . md5($token) . '_' . $id;
        
        // Tenta cache privado (1 hora para garantir permissões frescas)
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            // Fetch in Parallel
            $responses = Http::pool(fn (\Illuminate\Http\Client\Pool $pool) => [
                $pool->as('lib')->withToken($token)->timeout(12)->get($this->apiUrl . '/catalog/libraries/' . $id),
                $pool->as('cat')->withToken($token)->timeout(12)->get($this->apiUrl . '/catalog/products/' . $id),
                $pool->as('items')->withToken($token)->timeout(12)->get($this->apiUrl . '/admin/products/' . $id . '/items'),
            ]);

            $responseLib = $responses['lib'];
            
            if ($responseLib instanceof \Throwable || $responses['cat'] instanceof \Throwable || $responses['items'] instanceof \Throwable) {
                throw new \Exception('Falha de conexão de API em produtocomprado');
            }

            if ($responseLib->status() == 401 || (isset($responseLib->object()->code) && $responseLib->object()->code == 'unauthorized')) {
                session()->flush();
                return 'deslogou';
            }

            $conteudo = $responseLib->object();
            $conteudo_catalogo = $responses['cat']->object();
            $conteudo_itens = $responses['items']->object();

            if (is_object($conteudo)) {
                if (!empty($conteudo->cover)) {
                    $conteudo->cover = $this->generateThumborUrl($conteudo->cover);
                }
                if (isset($conteudo->productItems)) {
                    foreach ($conteudo->productItems as $key => $value) {
                        if (!empty($value->cover)) {
                            $conteudo->productItems[$key]->cover = $this->generateThumborUrl($value->cover);
                        }
                    }
                }
            }

            // Map items by ID for O(1) lookup in Blade
            $mappedItems = [];
            if (is_array($conteudo_itens)) {
                foreach ($conteudo_itens as $item) {
                    if (isset($item->id)) {
                        $mappedItems[$item->id] = $item;
                    }
                }
            }

            $conteudo_final = array($conteudo, $conteudo_catalogo, $mappedItems);
            
            if (is_object($conteudo) && !empty($conteudo->id)) {
                Cache::put($cacheKey, $conteudo_final, 3600); // 1 hora
            }

            return $conteudo_final;
        } catch (\Exception $e) {
            Log::warning('Fallback Ativado em produtocomprado (' . $id . '): ' . $e->getMessage());
            return [null, null, null];
        }
    }

    private function generateThumborUrl($imageUrl): string
    {
        $thumbor = new Thumbor($this->thumborUrl, $this->thumborKey);
        $thumbor->resize(Resize::ORIG, 450);
        $thumbor->fit(Fit::FIT_IN);
        $thumbor->imageUrl($imageUrl);
        return $thumbor->get();
    }
}
