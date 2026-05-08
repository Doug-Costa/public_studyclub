<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Beeyev\Thumbor\Thumbor;
use Beeyev\Thumbor\Manipulations\Resize;
use Beeyev\Thumbor\Manipulations\Fit;
use Session;

class ParceiroController extends Controller
{
    private string $apiUrl = 'https://api.dentalgo.com.br';

    public function canal($id)
    {
        $canal = 'canal'.$id;
        $backupCanal = 'backup_canal'.$id;
        $token = Cache::get('tokenGlobal');

        // 1. TENTA CACHE PRIMÁRIO
        if (Cache::has($canal)) {
            $cachedValue = Cache::get($canal);
            if ($this->validateCache($cachedValue)) {
                return $cachedValue;
            }
            Cache::forget($canal);
        }

        // 2. TENTA API
        try {
            $response = Http::withToken($token)
                ->timeout(10)
                ->get($this->apiUrl . '/catalog/collections/' . $id . '/divulgation');
            
            $conteudo = $response->object();

            if ($response->successful() && is_object($conteudo) && isset($conteudo->title) && isset($conteudo->products)) {
                
                if(!empty($conteudo->cover)){
                    $conteudo->cover = $this->generateThumborUrl($conteudo->cover);
                }

                $canalTitulo = $conteudo->title;
                $canalCapa = $conteudo->cover;
                $canalDescricao = $conteudo->brief;
                $materias = array();
                $videos = array();

                // SEPARA O QUE É MATERIA E O QUE É VIDEO
                foreach ($conteudo->products as $product) {
                    if ($product->internalCode === 'materia') {
                        if(!empty($product->cover)){
                            $product->cover = $this->generateThumborUrl($product->cover);
                        }
                        if (isset($product->productItems)) {
                            foreach ($product->productItems as $productItem) {
                                if(!empty($productItem->cover)){
                                    $productItem->cover = $this->generateThumborUrl($productItem->cover);
                                }
                            }
                        }
                        $materias[] = $product;
                    } elseif ($product->internalCode === 'video') {
                        if(!empty($product->cover)) {
                            $product->cover = $this->generateThumborUrl($product->cover);
                        }
                        if (isset($product->productItems)) {
                            foreach ($product->productItems as $productItem) {
                                if(!empty($productItem->cover)) {
                                    $productItem->cover = $this->generateThumborUrl($productItem->cover);
                                }
                            }
                        }
                        $videos[] = $product;
                    }
                }

                $conteudo_final = array($conteudo, $canalTitulo, $canalCapa, $canalDescricao, $materias, $videos);
                
                Cache::put($canal, $conteudo_final, 864000); // 10 dias
                Cache::put($backupCanal, $conteudo_final, 2592000); // 30 dias (Backup de Segurança)
                
                return $conteudo_final;
            }
        } catch (\Exception $e) {
            // Se a API falhar, o código segue para o backup
        }

        // 3. FALLBACK: Tenta o Cache de Backup se a API falhou e não havia cache primário
        if (Cache::has($backupCanal)) {
            $backupValue = Cache::get($backupCanal);
            if ($this->validateCache($backupValue)) {
                return $backupValue;
            }
        }

        // Caso tudo falhe miseravelmente, retorna um array seguro para a view não crashar
        return [null, 'Canal indisponível', '', '', [], []];
    }

    private function validateCache($value): bool
    {
        return is_array($value) 
            && count($value) === 6 
            && !empty($value[1]); // Título do Canal deve estar presente
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