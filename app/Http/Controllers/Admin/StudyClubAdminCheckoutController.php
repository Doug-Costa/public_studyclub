<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Controller: StudyClubAdminCheckoutController
 * Checkout específico para equipe interna e admins do Study Club.
 * Independente do checkout de clientes.
 */
class StudyClubAdminCheckoutController extends Controller
{
    /**
     * Exibe a página de checkout para o Admin (sem travas de pagamento)
     */
    public function show($planId1 = null, $planId2 = null)
    {
        // Admins sempre têm acesso "Premium" na visualização
        $initialStep = 2; // Pula o login do cliente
        $usuario = (object)[
            'name' => session('studyclub_admin_name'),
            'email' => session('studyclub_admin_username'),
            'tipoUsuario' => 'admin_interno'
        ];
        
        $filteredPlansObject = (object)['plans' => []];
        $allowedPlanIds = array_filter([$planId1, $planId2], function($v){ return $v !== null && $v !== ''; });

        if (empty($allowedPlanIds)) {
            $allowedPlanIds = [262]; // Plano padrão para testes
        }

        try {
            // Busca planos para exibição (mesma API, mas sem travas)
            $response = Http::get('https://api.dentalgo.com.br/catalog/plans');
            if ($response->successful()) {
                $allPlansData = $response->object();
                if (isset($allPlansData->plans) && is_array($allPlansData->plans)) {
                    $filteredPlans = array_filter($allPlansData->plans, function($plan) use ($allowedPlanIds) {
                        return isset($plan->id) && in_array($plan->id, $allowedPlanIds);
                    });
                    $filteredPlansObject->plans = array_values($filteredPlans);
                }
            }
        } catch (\Exception $e) {
            Log::error('Erro no checkout de admin: ' . $e->getMessage());
        }

        return view('admin.studyclub.checkout', [
            'initialStep' => $initialStep,
            'usuario' => $usuario,
            'plans' => $filteredPlansObject,
            'subscriptionStatus' => 'active', // Bypass total
            'checkoutType' => 'plan',
            'partialItems' => [],
            'partialTotalPrice' => 0,
            'subscriberPurchaseDiscountPercent' => 0,
            'isAdmin' => true
        ]);
    }

    /**
     * Simulação de salvamento de plano para Admin
     */
    public function savePlan(Request $request)
    {
        return response()->json(['success' => true, 'message' => 'Plano selecionado (Simulação Admin)']);
    }

    /**
     * Simulação de processamento de pagamento para Admin (Sempre Sucesso)
     */
    public function processPayment(Request $request)
    {
        Log::info('Processamento de pagamento fake para Admin: ' . session('studyclub_admin_name'));
        return response()->json([
            'success' => true, 
            'message' => 'Simulação de pagamento concluída com sucesso!'
        ]);
    }
}
