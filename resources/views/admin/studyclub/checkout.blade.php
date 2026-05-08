<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout Admin - StudyClub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/dentalgo.css') }}">
    <style>
        .step.hidden { display: none; }
        .plan-option.selected { border-color: #059669; background-color: #ecfdf5; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-4xl w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8 relative">
            <div class="absolute top-4 right-4">
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">MODO ADMINISTRATIVO</span>
            </div>

            <div class="w-full flex justify-center mb-8">
                <img src="{{ asset('imagens/LOGODENTALGO.fw.png') }}" alt="DentalGO" class="h-16 w-auto"/>
            </div>

            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-gray-800">Visualização de Checkout (Equipe Interna)</h1>
                <p class="text-gray-600">Olá, <strong>{{ $usuario->name }}</strong>. Você está visualizando o fluxo sem travas de pagamento.</p>
            </div>

            <div id="step-2" class="step">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Selecione o Plano (Simulação)</h3>
                <div id="plan-list" class="space-y-4">
                    @forelse($plans->plans as $plan)
                        <div class="plan-option border p-4 rounded-lg shadow cursor-pointer hover:border-green-500 transition-all" 
                             data-plan-id="{{ $plan->id }}" onclick="selectPlan(this)">
                            <div class="flex flex-col sm:flex-row items-start gap-4">
                                <div class="flex-1">
                                    <h4 class="font-semibold">{{ $plan->title }}</h4>
                                    <p class="text-sm text-gray-600">{{ $plan->description ?? 'Assinatura com renovação automática.' }}</p>
                                    <p class="text-green-600 font-bold mt-2">
                                        R$ {{ number_format(($plan->price / 100), 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="border p-4 rounded-lg bg-gray-50 text-gray-700 text-center">
                            <p>Nenhum plano disponível para esta visualização.</p>
                        </div>
                    @endforelse
                </div>
                <button type="button" id="btn-next" onclick="goToPayment()" class="mt-6 w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition opacity-50 cursor-not-allowed" disabled>
                    Continuar para Simulação de Pagamento
                </button>
            </div>

            <div id="step-3" class="step hidden">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Simulação de Pagamento</h2>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <p class="text-sm text-blue-700">
                        Como admin, você não precisa inserir dados de cartão reais. Clique no botão abaixo para concluir a simulação.
                    </p>
                </div>
                
                <div class="border rounded-lg p-6 bg-gray-50 mb-6">
                    <h3 class="font-semibold text-lg mb-3">Resumo do Pedido</h3>
                    <div class="flex justify-between items-center">
                        <span id="summary-title" class="text-gray-700">Plano Selecionado</span>
                        <span id="summary-price" class="font-bold text-gray-900">R$ 0,00</span>
                    </div>
                </div>

                <button type="button" onclick="finishSimulation()" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                    Finalizar Simulação
                </button>
                <button type="button" onclick="backToPlans()" class="w-full mt-2 text-gray-500 text-sm hover:underline">
                    Voltar para Planos
                </button>
            </div>
            
            <div id="success-message" class="step hidden text-center py-8">
                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Simulação Concluída!</h2>
                <p class="text-gray-600 mb-6">O fluxo de checkout foi validado com sucesso para este plano.</p>
                <a href="{{ route('admin.studyclub.index') }}" class="inline-block bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition">Voltar ao Painel Admin</a>
            </div>
        </div>
    </div>

    <script>
        let selectedPlanId = null;
        let selectedPlanTitle = '';
        let selectedPlanPrice = '';

        function selectPlan(element) {
            document.querySelectorAll('.plan-option').forEach(opt => opt.classList.remove('selected'));
            element.classList.add('selected');
            selectedPlanId = element.dataset.planId;
            selectedPlanTitle = element.querySelector('h4').innerText;
            selectedPlanPrice = element.querySelector('.text-green-600').innerText;
            
            const btn = document.getElementById('btn-next');
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        function goToPayment() {
            document.getElementById('step-2').classList.add('hidden');
            document.getElementById('step-3').classList.remove('hidden');
            document.getElementById('summary-title').innerText = selectedPlanTitle;
            document.getElementById('summary-price').innerText = selectedPlanPrice;
        }

        function backToPlans() {
            document.getElementById('step-3').classList.add('hidden');
            document.getElementById('step-2').classList.remove('hidden');
        }

        async function finishSimulation() {
            try {
                const response = await fetch("{{ route('admin.studyclub.checkout.processPayment') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ plan_id: selectedPlanId })
                });
                const data = await response.json();
                if (data.success) {
                    document.getElementById('step-3').classList.add('hidden');
                    document.getElementById('success-message').classList.remove('hidden');
                }
            } catch (error) {
                alert('Erro na simulação');
            }
        }
    </script>
</body>
</html>
