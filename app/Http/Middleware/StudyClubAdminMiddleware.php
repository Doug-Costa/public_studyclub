<?php

namespace App\Http\Middleware;

use App\Models\StudyClubAdmin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: StudyClubAdminMiddleware
 * Verifica se o usuário logado tem permissão de admin no Study Club
 */
class StudyClubAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se está logado
        if (!session()->has('token') || !session()->has('usuario')) {
            return redirect()->route('login.form')
                ->withErrors(['Acesso negado. Faça login primeiro.']);
        }

        $usuario = session()->get('usuario');
        $email = $usuario->email ?? null;

        // Verificar se o email está na lista de admins
        if (!$email || !StudyClubAdmin::isAdmin($email)) {
            abort(403, 'Você não tem permissão para acessar o admin do Study Club.');
        }

        // Registrar último login do admin
        $admin = StudyClubAdmin::where('email', $email)->first();
        if ($admin) {
            $admin->recordLogin();
        }

        return $next($request);
    }
}
