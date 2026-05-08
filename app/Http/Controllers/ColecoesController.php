<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Beeyev\Thumbor\Thumbor;
use Beeyev\Thumbor\Manipulations\Resize;
use Beeyev\Thumbor\Manipulations\Fit;
use Session;

class ColecoesController extends Controller
{
    private string $apiUrl = 'https://api.dentalgo.com.br';
    private string $thumborUrl = 'https://thumbor.dentalgo.com.br/';
    private string $thumborKey = '8e965d636dc76246b';

    public function colecoes()
    {
        $locale = in_array(session()->get('lang_code'), ['pt', 'en', 'es']) ? session()->get('lang_code') : '';
        $colecoesKey = 'colecoes' . $locale;
        $backupKey = 'backup_colecoes' . $locale;
        $token = Cache::get('tokenGlobal');

        // 1. TENTA CACHE PRIMÁRIO
        if (Cache::has($colecoesKey)) {
            $cachedValue = Cache::get($colecoesKey);
            if ($this->validateCacheColecoes($cachedValue)) {
                return array($cachedValue, '');
            }
            Cache::forget($colecoesKey);
        }

        // 2. TENTA API
        try {
            usleep(200000); // Pacing para poupar a API (200ms)
            $response = Http::withToken($token)
                ->timeout(10)
                ->get($this->apiUrl . '/subscription?page=1&locale=');
                
            $conteudo = $response->object();

            if ($response->successful() && is_object($conteudo) && isset($conteudo->collections)) {
                foreach ($conteudo->collections->magazines as $key => $value) {
                    if (!empty($value->cover)) {
                        $conteudo->collections->magazines[$key]->cover = $this->generateThumborUrl($value->cover);
                    }
                    if (!empty($value->lastProductCover)) {
                        $conteudo->collections->magazines[$key]->lastProductCover = $this->generateThumborUrl($value->lastProductCover);
                    }
                }

                if ($this->validateCacheColecoes($conteudo)) {
                    Cache::put($colecoesKey, $conteudo, 864000); // 10 dias
                    Cache::put($backupKey, $conteudo, 2592000); // 30 dias
                    return array($conteudo, '');
                }
            }
        } catch (\Exception $e) {
            // Fallback silencioso
        }

        // 3. FALLBACK: Tenta Backup
        if (Cache::has($backupKey)) {
            $backupValue = Cache::get($backupKey);
            if ($this->validateCacheColecoes($backupValue)) {
                return array($backupValue, '');
            }
        }

        // Fallback final: Objeto vazio para a view não crashar
        $fallback = (object) [
            'collections' => (object) [
                'magazines' => []
            ]
        ];
        return array($fallback, '');
    }

    private function validateCacheColecoes($value): bool
    {
        if (!is_object($value) || !isset($value->collections) || !isset($value->collections->magazines)) return false;
        
        // Só invalida se for explicitamente um erro de autorização
        if (isset($value->code) && $value->code === 'unauthorized') return false;
        
        return true;
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
