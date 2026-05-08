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

class ColecaoController extends Controller
{
    private string $apiUrl = 'https://api.dentalgo.com.br';
    private string $thumborUrl = 'https://thumbor.dentalgo.com.br/';
    private string $thumborKey = '8e965d636dc76246b';

    public function colecao($id)
    {
        @set_time_limit(120);
        $locale = in_array(session()->get('lang_code'), ['pt', 'en', 'es']) ? session()->get('lang_code') : '';
        $colecao = 'colecao' . $id . '' . $locale;
        $backupColecao = 'backup_colecao' . $id . '' . $locale;
        $token = Cache::get('tokenGlobal');

        // 1. TENTA CACHE PRIMÁRIO
        if (Cache::has($colecao.'c') && Cache::has($colecao.'r')) {
            $cachedValue = [Cache::get($colecao.'c'), Cache::get($colecao.'r')];
            if ($this->validateCacheColecao($cachedValue)) {
                return $cachedValue;
            }
            Cache::forget($colecao.'c');
            Cache::forget($colecao.'r');
        }

        // 2. TENTA API COM LOCK (Para evitar que múltiplos usuários batam na API ao mesmo tempo)
        $lockName = 'lock_colecao_' . $id . $locale;
        $lock = Cache::lock($lockName, 30); // Tranca por 30s

        if ($lock->get()) {
            try {
                // Fetch in Parallel to save time and add timeouts
                $responses = Http::pool(fn (\Illuminate\Http\Client\Pool $pool) => [
                    $pool->as('col')->withToken($token)->timeout(120)->get($this->apiUrl . '/subscription/collections/' . $id),
                    $pool->as('prod')->withToken($token)->timeout(120)->get($this->apiUrl . '/subscription/collections/' . $id . '/products', [
                        'page' => 1,
                        'locale' => '' 
                    ]),
                ]);

                $responseColecao = $responses['col'];
                $responseProdutos = $responses['prod'];

                if ($responseColecao instanceof \Throwable || $responseProdutos instanceof \Throwable) {
                    $m = $responseColecao instanceof \Throwable ? $responseColecao->getMessage() : $responseProdutos->getMessage();
                    throw new \Exception("Falha de rede. " . $m);
                }

                if ($responseColecao->status() === 401 || $responseProdutos->status() === 401) {
                    session()->flush();
                    $lock->release();
                    return 'deslogou';
                }

                if (!$responseColecao->successful() || !$responseProdutos->successful()) {
                    throw new \Exception("Status não esperado (Col: {$responseColecao->status()}, Prod: {$responseProdutos->status()})");
                }

                $conteudoColecao = $responseColecao->object();
                $conteudo = $responseProdutos->object();

                if (is_object($conteudoColecao) && isset($conteudoColecao->products)) {
                    foreach ($conteudoColecao->products as $key => $value) {
                        if (!empty($value->cover)) {
                            $conteudoColecao->products[$key]->cover = $this->generateThumborUrl($value->cover);
                        }
                    }
                }

                if (is_object($conteudo)) {
                    foreach ($conteudo as $key2 => $value2) {
                        if (is_array($value2)) {
                            foreach ($value2 as $key => $value) {
                                if (is_object($value) && !empty($value->cover)) {
                                    $conteudo->{$key2}[$key]->cover = $this->generateThumborUrl($value->cover);
                                }
                            }
                        } elseif (is_object($value2)) {
                            if (!empty($value2->cover)) {
                                $conteudo->{$key2}->cover = $this->generateThumborUrl($value2->cover);
                            }
                        }
                    }
                }

                $conteudo_final = [$conteudoColecao, $conteudo];

                if ($this->validateCacheColecao($conteudo_final)) {
                    Cache::put($colecao.'c', $conteudoColecao, 864000);
                    Cache::put($colecao.'r', $conteudo, 864000);
                    Cache::put($backupColecao.'c', $conteudoColecao, 2592000);
                    Cache::put($backupColecao.'r', $conteudo, 2592000);
                    $lock->release();
                    return $conteudo_final;
                }
            } catch (\Exception $e) {
                \Log::warning('Fallback Ativado em ColecaoController: Acesso à API falhou. ' . $e->getMessage());
            } finally {
                $lock->release();
            }
        }

        // 3. FALLBACK: Tenta o Cache de Backup (Se API falhar ou se alguém já estiver atualizando)
        if (Cache::has($backupColecao.'c') && Cache::has($backupColecao.'r')) {
            $backupValue = [Cache::get($backupColecao.'c'), Cache::get($backupColecao.'r')];
            if ($this->validateCacheColecao($backupValue)) {
                return $backupValue;
            }
        }

        return [null, null];
    }

    private function validateCacheColecao($value): bool
    {
        if (!is_array($value) || count($value) !== 2) return false;
        
        // Verifica se a primeira parte (colecao) é objeto e válida
        if (empty($value[0]) || !is_object($value[0])) return false;
        if (isset($value[0]->code) && $value[0]->code === 'unauthorized') return false;
        
        // Verifica se a segunda parte (produtos) não indica erro de autorizacao (pode ser array dependendo da paginação)
        if (is_object($value[1]) && isset($value[1]->code) && $value[1]->code === 'unauthorized') return false;
        
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
