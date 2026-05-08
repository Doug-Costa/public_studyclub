<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Session;

class FlushController extends Controller
{
    private const FLUSH_PASS = 'godental2026@';

    /**
     * Exibe o Painel de Flush ou executa a ação de limpeza.
     */
    public function cache(Request $request)
    {
        // 1. Verificação de Senha
        if (!session()->get('flush_authorized')) {
            if ($request->isMethod('post') && $request->input('auth_password') === self::FLUSH_PASS) {
                session()->put('flush_authorized', true);
                return redirect()->route('flush_cache')->with('success', 'Acesso autorizado.');
            }

            if ($request->isMethod('post') && $request->has('auth_password')) {
                return redirect()->route('flush_cache')->with('error', 'Senha incorreta.');
            }

            return view('facelift2.flush', ['needsAuth' => true]);
        }

        // 2. Ações de Flush (Apenas se autorizado)
        if ($request->isMethod('post')) {
            $action = $request->input('action');
            $id = $request->input('id'); 
            $status = 'success';
            $message = '';

            switch ($action) {
                case 'logout':
                    session()->forget('flush_authorized');
                    return redirect()->route('home')->with('success', 'Sessão de Flush encerrada.');

                case 'all':
                    Cache::flush();
                    Artisan::call('route:clear');
                    $message = 'Toda a CACHE do sistema foi limpa com sucesso!';
                    break;

                case 'home':
                    Cache::forget('faceindex_global_data_pt');
                    Cache::forget('faceindex_global_data_en');
                    Cache::forget('faceindex_global_data_es');
                    $message = 'Cache da Home Page limpo (PT/EN/ES).';
                    break;

                case 'videos':
                    Cache::forget('canais');
                    Cache::forget('canaispt');
                    Cache::forget('canaisen');
                    Cache::forget('canaises');
                    Cache::forget('backup_canais');
                    Cache::forget('backup_canaispt');
                    Cache::forget('backup_canaisen');
                    Cache::forget('backup_canaises');
                    $message = 'Cache de Vídeos e Canais (Primário e Backup) limpo.';
                    break;

                case 'livros':
                    Cache::forget('livros');
                    Cache::forget('livrospt');
                    Cache::forget('livrosen');
                    Cache::forget('livroses');
                    Cache::forget('backup_livrospt');
                    Cache::forget('backup_livrosen');
                    Cache::forget('backup_livroses');
                    $message = 'Cache de Livros (GoBooks) limpo.';
                    break;

                case 'colecoes':
                    Cache::forget('colecoes');
                    Cache::forget('colecoespt');
                    Cache::forget('colecoesen');
                    Cache::forget('colecoeses');
                    Cache::forget('backup_colecoespt');
                    Cache::forget('backup_colecoesen');
                    Cache::forget('backup_colecoeses');
                    $message = 'Cache da estrutura de Coleções limpo.';
                    break;

                case 'token':
                    Cache::forget('tokenGlobal');
                    $message = 'Token de API da DentalGo limpo. O sistema irá re-autenticar no próximo acesso.';
                    break;

                case 'individual_colecao':
                    if ($id) {
                        Cache::forget('colecao' . $id . 'c');
                        Cache::forget('colecao' . $id . 'r');
                        Cache::forget('backup_colecao' . $id . 'c');
                        Cache::forget('backup_colecao' . $id . 'r');
                        $message = "Cache da Coleção ID {$id} limpo com sucesso.";
                    } else {
                        $status = 'error';
                        $message = 'ID da coleção não informado.';
                    }
                    break;

                case 'individual_revista':
                    if ($id) {
                        Cache::forget('revista' . $id);
                        $message = "Cache da Revista ID {$id} limpo com sucesso.";
                    } else {
                        $status = 'error';
                        $message = 'ID da revista não informado.';
                    }
                    break;

                default:
                    $status = 'error';
                    $message = 'Ação de flush inválida.';
            }

            return redirect()->route('flush_cache')->with($status, $message);
        }

        return view('facelift2.flush', ['needsAuth' => false]);
    }
}