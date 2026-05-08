<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: StudyClubLocalAuthMiddleware
 * Verifica se está logado no sistema LOCAL do Study Club (independente do DentalGo)
 */
class StudyClubLocalAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar sessão local do Study Club
        if (!session()->has('studyclub_admin_id')) {
            return redirect()->route('studyclub.admin.login')
                ->withErrors(['Acesso negado. Faça login primeiro.']);
        }

        return $next($request);
    }
}
