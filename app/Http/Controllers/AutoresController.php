<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Beeyev\Thumbor\Thumbor;
use Beeyev\Thumbor\Manipulations\Resize;
use Beeyev\Thumbor\Manipulations\Fit;
use Session;

class AutoresController extends Controller
{
    private string $apiUrl = 'https://api.dentalgo.com.br';

    public function autor($id)
    {
        $locale = in_array(session()->get('lang_code'), ['pt', 'en', 'es']) ? session()->get('lang_code') : '';
        $id_autor = $id;
        $autorKey = 'autor' . $id_autor . '' . $locale;
        $backupKey = 'backup_autor' . $id_autor . '' . $locale;
        $token = Cache::get('tokenGlobal');

        // 1. TENTA CACHE PRIMÁRIO
        if (Cache::has($autorKey)) {
            $cachedValue = Cache::get($autorKey);
            if ($this->validateCacheAutor($cachedValue)) {
                return $cachedValue;
            }
            Cache::forget($autorKey);
        }

        // 2. TENTA API
        try {
            $respAutor = Http::withToken($token)->timeout(10)->get($this->apiUrl . '/catalog/authors/' . $id_autor);
            $conteudo_autor = $respAutor->object();

            if ($respAutor->successful() && is_object($conteudo_autor)) {
                if (!empty($conteudo_autor->photoURL)) {
                    $conteudo_autor->photoURL = $this->generateThumborUrl($conteudo_autor->photoURL);
                }
 
                usleep(200000); // Pacing antes de buscar produtos do autor
                $autorNome = str_replace(" ", "%20", $conteudo_autor->name);
                $respSearch = Http::withToken($token)->timeout(10)->get($this->apiUrl . '/subscription/search/complete', [
                    'take' => 100,
                    'q[query]' => $autorNome
                ]);
                $conteudo_pesquisa = $respSearch->object();

                $conteudo_pesquisa_final = $conteudo_pesquisa->rows ?? [];

                // Paginação se necessário
                if (isset($conteudo_pesquisa->pages) && $conteudo_pesquisa->pages > 1) {
                    $maxPages = min($conteudo_pesquisa->pages, 10); // Limite de 10 páginas para segurança
                    for ($i = 2; $i <= $maxPages; $i++) {
                        usleep(250000); // Pacing entre páginas (250ms)
                        $respPage = Http::withToken($token)->timeout(10)->get($this->apiUrl . '/subscription/search/complete', [
                            'page' => $i,
                            'take' => 30,
                            'q[query]' => $autorNome
                        ]);
                        $conteudo_pagina = $respPage->object();
                        
                        if (isset($conteudo_pagina->code) && $conteudo_pagina->code === 'unauthorized') {
                            break; // Para se o token expirar
                        }
                        
                        if (isset($conteudo_pagina->rows)) {
                            $conteudo_pesquisa_final = array_merge($conteudo_pesquisa_final, $conteudo_pagina->rows);
                        }
                    }
                }

                $produtos_pesquisa = [
                    'book' => [],
                    'video' => [],
                    'magazine' => []
                ];

                foreach ($conteudo_pesquisa_final as $key => $value) {
                    $temAutor = false;
                    if (isset($value->authors)) {
                        foreach ($value->authors as $author) {
                            if ($author->id == $conteudo_autor->id) {
                                $temAutor = true;
                                break;
                            }
                        }
                    }

                    if ($temAutor) {
                        $cover = !empty($value->cover) ? $this->generateThumborUrl($value->cover) : ($value->cover ?? '');
                        $cover_produto = (isset($value->product) && !empty($value->product->cover)) ? $this->generateThumborUrl($value->product->cover) : ($value->product->cover ?? '');

                        $produtos_pesquisa[$value->productType][] = [
                            'id_artigo' => $value->id,
                            'id_produto' => $value->product->id ?? null,
                            'title' => $value->title ?? '',
                            'title_produto' => $value->product->title ?? '',
                            'cover' => $cover,
                            'cover_produto' => $cover_produto,
                            'brief_produto' => $value->product->brief ?? '',
                            'brief' => $value->brief ?? ''
                        ];
                    }
                }

                $retorno = array($conteudo_autor, $produtos_pesquisa);

                if ($this->validateCacheAutor($retorno)) {
                    Cache::put($autorKey, $retorno, 864000); // 10 dias
                    Cache::put($backupKey, $retorno, 2592000); // 30 dias
                    return $retorno;
                }
            }
        } catch (\Exception $e) {
            // Fallback silencioso
        }

        // 3. FALLBACK: Tenta Backup
        if (Cache::has($backupKey)) {
            $backupValue = Cache::get($backupKey);
            if ($this->validateCacheAutor($backupValue)) {
                return $backupValue;
            }
        }

        return [null, ['book' => [], 'video' => [], 'magazine' => []]];
    }

    private function validateCacheAutor($value): bool
    {
        if (!is_array($value) || count($value) !== 2) return false;
        
        // Valida autor
        if (empty($value[0]) || !is_object($value[0])) return false;
        if (isset($value[0]->code) && $value[0]->code === 'unauthorized') return false;
        
        return is_array($value[1]);
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
 