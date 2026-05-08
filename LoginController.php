<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Session;
use App\Mail\ExemploEmail;

class LoginController extends Controller
{
    private string $apiUrl = 'https://api.dentalgo.com.br';

    public function login($params = [])
    {
        if(empty($params)){
            $email = request()->input('email');
            $password = request()->input('password');
            $tipoUsuario = 'pessoal';
        } else {
            $email = $params['usuario'];
            $password = $params['senha'];
            $tipoUsuario = 'institucional';
        }

        $response = Http::asForm()->post($this->apiUrl . '/sessions/sign-in', [
            'email' => $email,
            'password' => $password,
        ]);

        $retorno = $response->object();
        
        if(isset($retorno->token)){
            session()->put('token', $retorno->token);
            return $this->processUserSubscription($retorno->token, $tipoUsuario);
        } else {
            return $this->handleLoginErrors($retorno);
        }
    }

    public function loginAuto($barear = null)
    {
        if(empty($barear)){
            $email = request()->input('email');
            $password = request()->input('password');

            $response = Http::asForm()->post($this->apiUrl . '/sessions/sign-in', [
                'email' => $email,
                'password' => $password,
            ]);

            $retorno = $response->object();

            if(isset($retorno->token)){
                session()->put('token', $retorno->token);
            }
        } else {
            session()->flush();
            session()->put('token', $barear);
        }

        $token = session()->get('token');

        if(!empty($token)){
            return $this->processUserSubscription($token, 'pessoal');
        } else {
            $retorno = isset($retorno) ? $retorno : null;
            return $this->handleLoginErrors($retorno);
        }
    }

    private function processUserSubscription($token, $tipoUsuario)
    {
        $response = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->get($this->apiUrl . '/account/current-user');

        if ($response->successful()) {
            $retorno = $response->object();
        } else {
            return back()->withErrors('Erro ao obter informações do usuário.')->withInput();
        }

        $retorno->tipoUsuario = $tipoUsuario;
        session()->put('usuario', $retorno);

        if(isset($retorno->subscription->planId)){
            if($retorno->subscription->status == 'canceled'){
                session()->put('usuarioPlano', 'venceu');
                session()->put('usuarioPermissao', 'naotemVencido');
                return back()->withErrors('logadoVencido')->withInput();
            } else {
                $idPlano = $retorno->subscription->planId;

                $responseCol = Http::withToken($token)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->get($this->apiUrl . '/catalog/plans/'.$idPlano.'/collections', [
                        'page' => 1,
                    ]);

                if ($responseCol->successful()) {
                    $retorno_plano = $responseCol->object();
                } else {
                    return back()->withErrors('Erro ao obter as coleções do plano.')->withInput();
                }

                $colecaoPermissao = array();
                if(isset($retorno_plano->rows)){
                    foreach ($retorno_plano->rows as $key => $colecao) {
                        $colecaoPermissao[$key] = $colecao->id;
                    }
                }

                $responseSub = Http::withToken($token)->get($this->apiUrl . '/subscription');
                
                if ($responseSub->successful()) {
                    $retornoVerifica = $responseSub->object();
                } else {
                    $statusCode = $responseSub->status();
                    $errorBody = $responseSub->body();
                    Log::error("Erro ao verificar a assinatura: HTTP $statusCode - $errorBody");
                    return back()->withErrors('Erro ao verificar a assinatura.')->withInput();
                }

                session()->put('usuarioPlanoID', $idPlano);

                if(isset($retornoVerifica->code)){
                    if($retornoVerifica->code == 'subscriptionExpired'){
                        session()->put('usuarioPlano', 'venceu');
                        session()->put('usuarioPermissao', 'naotemVencido');
                        return back()->withErrors('logadoVencido')->withInput();
                    } elseif($retornoVerifica->code == 'routeNotFound'){
                        session()->put('usuarioPlano', $retorno_plano);
                        session()->put('usuarioPermissao', $colecaoPermissao);
                        return back()->withErrors('logado')->withInput();
                    }
                }

                session()->put('usuarioPlano', $retorno_plano);
                session()->put('usuarioPermissao', $colecaoPermissao);
                return back()->withErrors('logado')->withInput();
            }
        } else {
            session()->put('usuarioPlano', 'semplano');
            session()->put('usuarioPermissao', 'naotemSemPlano');
            return back()->withErrors('logadoSem')->withInput();
        }
    }

    private function handleLoginErrors($retorno)
    {
        if(isset($retorno->code)){
            if($retorno->code == 'userNotFound'){
                return back()->withErrors('errousuario')->withInput();
            } elseif($retorno->code == 'wrongPassword'){
                return back()->withErrors('errosenha')->withInput();
            } elseif($retorno->code == 'requestNewPassword'){
                return back()->withErrors('errosenhaNova')->withInput();
            } else {
                return $retorno;
            }
        } else {
            return $retorno;
        }
    }

    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $response = Http::asForm()->post($this->apiUrl . '/sessions/sign-in', [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        if ($response->successful() && isset($response->object()->token)) {
            $retornoApi = $response->object();
            session()->put('token', $retornoApi->token);
            session()->put('usuario', $retornoApi);
            return response()->json(['success' => true, 'message' => 'Login realizado!']);
        } else {
            $errorMessage = 'Credenciais inválidas.';
            if(isset($response->object()->code)) {
                $errorCode = $response->object()->code;
                if($errorCode == 'userNotFound') $errorMessage = 'Usuário não encontrado.';
                if($errorCode == 'wrongPassword') $errorMessage = 'Senha incorreta.';
            }
            return response()->json(['success' => false, 'message' => $errorMessage], 422);
        }
    }

    public function logout()
    {
        Http::withToken(session()->get('token'))
            ->delete($this->apiUrl . '/sessions/sign-out');
            
        session()->flush();
        return redirect()->back();
    }

    public function recsenha()
    {
        $email = request()->input('email');
        $token = Cache::get('tokenGlobal');

        $response = Http::withToken($token)
            ->get($this->apiUrl . '/admin/people', [
                'q[email]' => $email,
                'q[admin]' => 0,
            ]);

        if ($response->successful()) {
            $conteudo = $response->object();

            if (isset($conteudo->rows)) {
                foreach ($conteudo->rows as $key => $valores) {
                    if($valores->email == $email){
                        $destinatario = $email;
                        $assunto = 'E-mail de recuperação de senha DentalGo';
                        $nomeUsuario = $valores->fullName;

                        $linkRecuperacao = 'https://dentalgo.com.br/recuperarsenha?cod='.base64_encode($valores->id);

                        Mail::to($destinatario)->send(new ExemploEmail($assunto, $nomeUsuario, $linkRecuperacao));
                        
                        return back()->withErrors('recSenhaSucess')->withInput();
                    }
                }
            }
        }

        return back()->withErrors('recSenhaErro')->withInput();
    }
}