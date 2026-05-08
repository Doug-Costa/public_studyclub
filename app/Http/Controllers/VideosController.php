<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Beeyev\Thumbor\Thumbor;
use Beeyev\Thumbor\Manipulations\Resize;
use Session;

class VideosController extends Controller
{
    private string $apiUrl = 'https://api.dentalgo.com.br';
    private string $thumborUrl = 'https://thumbor.dentalgo.com.br/';
    private string $thumborKey = '8e965d636dc76246b';

    public function videos()
    {
        $locale = in_array(session()->get('lang_code'), ['pt', 'en', 'es']) ? session()->get('lang_code') : '';
        $canaisKey = 'canais' . $locale;
        $backupKey = 'backup_canais' . $locale;
        $token = Cache::get('tokenGlobal');

        // 1. TENTA CACHE PRIMÁRIO
        if (Cache::has($canaisKey)) {
            $cachedValue = Cache::get($canaisKey);
            if ($this->validateCacheVideos($cachedValue)) {
                return $cachedValue;
            }
            Cache::forget($canaisKey);
        }

        // 2. TENTA API COM LOCK
        $lock = Cache::lock('lock_videos_' . $locale, 30);
        if ($lock->get()) {
            try {
                usleep(300000); // Fôlego inicial (300ms)
                $response = Http::withToken($token)->timeout(10)->get($this->apiUrl . '/subscription/videos');
                $conteudo = $response->object();

                if ($response->successful() && (is_array($conteudo) || is_object($conteudo))) {
                    foreach ($conteudo as $key2 => $value2) {
                        if (!empty($conteudo[$key2]->cover)) {
                            $conteudo[$key2]->cover = $this->generateThumborUrl($conteudo[$key2]->cover);
                        }
                        if (isset($value2->productItems)) {
                            foreach ($value2->productItems as $key => $value) {
                                if (!empty($conteudo[$key2]->productItems[$key]->cover)) {
                                    $conteudo[$key2]->productItems[$key]->cover = $this->generateThumborUrl($conteudo[$key2]->productItems[$key]->cover);
                                }
                            }
                        }
                    }

                    if ($this->validateCacheVideos($conteudo)) {
                        Cache::put($canaisKey, $conteudo, 864000); // 10 dias
                        Cache::put($backupKey, $conteudo, 2592000); // 30 dias
                        $lock->release();
                        return $conteudo;
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Erro ao buscar vídeos: ' . $e->getMessage());
            } finally {
                $lock->release();
            }
        }

        // 3. FALLBACK: Tenta Backup (Se API falhar ou Lock ocupado)
        if (Cache::has($backupKey)) {
            $backupValue = Cache::get($backupKey);
            if ($this->validateCacheVideos($backupValue)) {
                return $backupValue;
            }
        }

        return [];
    }

    public function video($id)
    {
        $locale = in_array(session()->get('lang_code'), ['pt', 'en', 'es']) ? session()->get('lang_code') : '';
        $canalKey = 'canal' . $id . '' . $locale;
        $backupKey = 'backup_canal' . $id . '' . $locale;
        $token = Cache::get('tokenGlobal');

        // 1. TENTA CACHE PRIMÁRIO
        if (Cache::has($canalKey)) {
            $cachedValue = Cache::get($canalKey);
            if ($this->validateCacheSingleVideo($cachedValue)) {
                return $cachedValue;
            }
            Cache::forget($canalKey);
        }

        // 2. TENTA API COM LOCK
        $lock = Cache::lock('lock_video_' . $id . $locale, 30);
        if ($lock->get()) {
            try {
                usleep(300000); // Pacing (300ms)
                $response = Http::withToken($token)->timeout(10)->get($this->apiUrl . '/subscription/products/' . $id);
                $conteudo = $response->object();

                if ($response->successful() && is_object($conteudo) && isset($conteudo->productItems)) {
                    // Ordenação segura
                    $ord = [];
                    foreach ($conteudo->productItems as $key => $value) {
                        $ord[] = strtotime($value->createdAt ?? now());
                    }
                    if (!empty($conteudo->productItems)) {
                        array_multisort($ord, SORT_DESC, $conteudo->productItems);
                    }

                    // Thumbors
                    if (!empty($conteudo->cover)) {
                        $conteudo->cover = $this->generateThumborUrl($conteudo->cover);
                    }

                    foreach ($conteudo->productItems as $key => $value) {
                        if (!empty($conteudo->productItems[$key]->cover)) {
                            $conteudo->productItems[$key]->cover = $this->generateThumborUrl($conteudo->productItems[$key]->cover);
                        }
                    }

                    Cache::put($canalKey, $conteudo, 864000); // 10 dias
                    Cache::put($backupKey, $conteudo, 2592000); // 30 dias
                    $lock->release();
                    return $conteudo;
                }
            } catch (\Exception $e) {
                \Log::error('Erro ao buscar vídeo individual: ' . $e->getMessage());
            } finally {
                $lock->release();
            }
        }

        // 3. FALLBACK: Tenta Backup
        if (Cache::has($backupKey)) {
            $backupValue = Cache::get($backupKey);
            if ($this->validateCacheSingleVideo($backupValue)) {
                return $backupValue;
            }
        }

        return (object) [
            'id' => null,
            'title' => 'Vídeo não encontrado',
            'brief' => '™™™',
            'productItems' => [],
            'cover' => ''
        ];
    }

    private function validateCacheVideos($value): bool
    {
        return (is_array($value) || is_object($value)) && !empty($value);
    }

    private function validateCacheSingleVideo($value): bool
    {
        return is_object($value) && isset($value->productItems);
    }

    private function generateThumborUrl($imageUrl): string
    {
        $thumbor = new Thumbor($this->thumborUrl, $this->thumborKey);
        $thumbor->resize(Resize::ORIG);
        $thumbor->imageUrl($imageUrl);
        return $thumbor->get();
    }
}
