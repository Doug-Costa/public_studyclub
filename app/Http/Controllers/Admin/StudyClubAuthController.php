<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyClubLocalAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controller: StudyClubAuthController
 * Autenticação LOCAL e INDEPENDENTE para admin do Study Club
 */
class StudyClubAuthController extends Controller
{
    /**
     * Tela de login
     */
    public function showLogin()
    {
        // Se já está logado, redireciona para dashboard
        if (session()->has('studyclub_admin_id')) {
            return redirect()->route('admin.studyclub.index');
        }

        return view('admin.studyclub.login');
    }

    /**
     * Processar login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50',
            'password' => 'required|string|max:100',
        ]);

        $admin = StudyClubLocalAdmin::findByUsername($validated['username']);

        // Usuário não encontrado
        if (!$admin) {
            Log::warning('Tentativa de login Study Club - usuário não encontrado', [
                'username' => $validated['username'],
                'ip' => $request->ip(),
            ]);
            return back()->withErrors(['username' => 'Usuário ou senha inválidos']);
        }

        // Verificar bloqueio
        if ($admin->isLocked()) {
            $minutes = $admin->lockMinutesRemaining();
            return back()->withErrors([
                'username' => "Conta temporariamente bloqueada. Tente novamente em {$minutes} minutos."
            ]);
        }

        // Verificar senha
        if (!$admin->checkPassword($validated['password'])) {
            $admin->recordFailedAttempt();
            
            Log::warning('Tentativa de login Study Club - senha incorreta', [
                'username' => $validated['username'],
                'ip' => $request->ip(),
                'attempts' => $admin->login_attempts,
            ]);

            return back()->withErrors(['username' => 'Usuário ou senha inválidos']);
        }

        // Login bem-sucedido
        $admin->recordSuccess();
        
        // Criar sessão local do Study Club (separada do DentalGo)
        session()->put('studyclub_admin_id', $admin->id);
        session()->put('studyclub_admin_name', $admin->name);
        session()->put('studyclub_admin_username', $admin->username);
        
        Log::info('Login Study Club Admin bem-sucedido', [
            'username' => $admin->username,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('admin.studyclub.index')
            ->with('success', "Bem-vindo, {$admin->name}!");
    }

    /**
     * Logout
     */
    public function logout()
    {
        $username = session()->get('studyclub_admin_username');
        
        // Limpar sessão do Study Club
        session()->forget(['studyclub_admin_id', 'studyclub_admin_name', 'studyclub_admin_username']);
        
        Log::info('Logout Study Club Admin', ['username' => $username]);

        return redirect()->route('studyclub.admin.login')
            ->with('success', 'Logout realizado com sucesso!');
    }
}
