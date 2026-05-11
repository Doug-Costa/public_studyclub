@extends('facelift2.master')

@section('title', $item->title . ' - Study Club')

@section('content')
<div class="studyclub-article-page bg-gray-50 min-vh-100">
    {{-- Header do Artigo --}}
    <div class="bg-white border-bottom py-4 mb-5 shadow-sm">
        <div class="container">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('studyclub.index') }}" class="text-primary text-decoration-none">StudyClub</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('studyclub.edition', $edition->number) }}" class="text-primary text-decoration-none">{{ $edition->title }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Artigo</li>
                </ol>
            </nav>

            <div class="row align-items-center">
                <div class="col-12">
                    <span class="badge bg-primary px-3 py-2 mb-3 text-uppercase fw-bold">{{ $item->formatted_category }}</span>
                    <h1 class="display-5 fw-bold text-dark mb-4" style="line-height: 1.2;">{{ $item->title }}</h1>
                    
                    <div class="d-flex flex-wrap align-items-center gap-4 text-muted">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-2">
                                <i class="bi bi-person text-primary"></i>
                            </div>
                            <span class="small">Por <strong>{{ $item->author }}</strong></span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar3 me-2"></i>
                            <span class="small">{{ $edition->formatted_date }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-journal-bookmark me-2"></i>
                            <span class="small">Fonte: {{ $item->type_label ?? 'Artigo' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row g-5">
            {{-- Conteúdo Principal --}}
            <div class="col-lg-8">
                {{-- Primeira Seção: Imagem + Resumo --}}
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="rounded-4 overflow-hidden shadow-sm h-100">
                            <img src="{{ $item->image_url }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->title }}" style="min-height: 300px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 rounded-4 shadow-sm h-100 bg-white">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-2">
                                        <i class="bi bi-journal-text text-primary"></i>
                                    </div>
                                    <h4 class="fw-bold mb-0">Resumo do Artigo</h4>
                                </div>
                                <div class="text-gray-700 leading-relaxed">
                                    {{ $item->resumo }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Segunda Seção: Principais Achados --}}
                <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white border-start border-primary border-5" style="border-left-width: 8px !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-2">
                                <i class="bi bi-lightbulb text-primary fs-5"></i>
                            </div>
                            <h4 class="fw-bold mb-0">Principais Achados</h4>
                        </div>
                        <div class="fs-5 text-gray-700">
                            {{ $item->achados }}
                        </div>
                    </div>
                </div>

                {{-- Terceira Seção: Implicações Clínicas --}}
                <div class="card border-0 rounded-4 shadow-sm mb-5 bg-white border-start border-danger border-5" style="border-left-width: 8px !important; border-color: #db2777 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-danger bg-opacity-10 p-2 rounded-3 me-2">
                                <i class="bi bi-check2-circle text-danger fs-5"></i>
                            </div>
                            <h4 class="fw-bold mb-0">Implicações Clínicas</h4>
                        </div>
                        <div class="fs-5 text-gray-700">
                            {{ $item->implicacoes }}
                        </div>
                    </div>
                </div>

                {{-- Artigos Relacionados --}}
                <div class="mt-5 pt-5 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold">Outros Artigos Relacionados</h3>
                    </div>
                    <div class="row g-4">
                        @foreach($relatedArticles as $related)
                            <div class="col-md-6">
                                <a href="{{ route('studyclub.show', [$edition->number, $related->id]) }}" class="text-decoration-none">
                                    <div class="card border-0 rounded-4 shadow-sm overflow-hidden h-100 premium-card-hover">
                                        <div class="position-relative" style="height: 180px;">
                                            <img src="{{ $related->image_url }}" class="w-100 h-100 object-fit-cover" alt="{{ $related->title }}">
                                            <span class="badge bg-primary position-absolute bottom-0 start-0 m-3">{{ $related->formatted_category }}</span>
                                        </div>
                                        <div class="card-body p-4">
                                            <h5 class="fw-bold text-dark mb-3">{{ Str::limit($related->title, 70) }}</h5>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-muted small">
                                                    <span class="me-2"><i class="bi bi-heart-fill text-danger"></i> {{ $related->likes }}</span>
                                                </div>
                                                <span class="btn btn-outline-primary btn-sm rounded-pill px-3">Ver Artigo</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px;">
                    {{-- Sidebar Categorias --}}
                    @include('studyclub.partials._sidebar_categories')

                    {{-- Newsletter --}}
                    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">StudyClub Newsletter</h5>
                            <p class="text-muted small mb-4">Receba os artigos selecionados toda quarta-feira.</p>
                            <form>
                                <div class="mb-3">
                                    <input type="email" class="form-control rounded-3 border-light bg-light" placeholder="Seu e-mail">
                                </div>
                                <button type="button" class="btn btn-primary w-100 rounded-pill fw-bold py-2" style="background-color: #4c1d95; border-color: #4c1d95;">Assinar</button>
                            </form>
                        </div>
                    </div>

                    {{-- Ações e Redes Sociais (NOVO CARD) --}}
                    <div class="card border-0 rounded-4 shadow-sm bg-white mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4 small text-uppercase tracking-wider">Interação & Acesso</h5>
                            
                            {{-- Like --}}
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="small fw-medium text-dark">Curtir Artigo</span>
                                    <span class="x-small text-muted" id="likes-text">Likes</span>
                                </div>
                                <button class="btn btn-{{ $item->is_liked ? 'danger' : 'outline-danger' }} w-100 rounded-pill py-2 fw-bold d-flex align-items-center justify-content-center" 
                                        id="btn-like" data-id="{{ $item->id }}" onclick="toggleLike()">
                                    <i class="bi bi-heart{{ $item->is_liked ? '-fill' : '' }} me-2"></i>
                                    <span id="likes-count">{{ $item->likes }}</span>
                                </button>
                            </div>

                            {{-- Artigo Original --}}
                            <div class="mb-4 pt-4 border-top">
                                <a href="{{ $item->external_url }}" target="_blank" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">
                                    <i class="bi bi-link-45deg me-1"></i> Artigo Original
                                </a>
                            </div>

                            {{-- Redes Sociais --}}
                            <div class="pt-4 border-top">
                                <p class="small fw-medium text-dark mb-3 text-center">Compartilhar Artigo</p>
                                <div class="d-flex justify-content-center gap-3">
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}" target="_blank" 
                                       class="btn btn-outline-primary rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 40px; height: 40px;">
                                        <i class="bi bi-linkedin fs-5"></i>
                                    </a>
                                    <a href="https://api.whatsapp.com/send?text={{ urlencode($item->title . ' - ' . request()->fullUrl()) }}" target="_blank" 
                                       class="btn btn-outline-success rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 40px; height: 40px;">
                                        <i class="bi bi-whatsapp fs-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleLike() {
        const btn = document.getElementById('btn-like');
        const itemId = btn.getAttribute('data-id');
        
        fetch(`/studyclub/items/${itemId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('likes-count').innerText = data.likesCount;
                const icon = btn.querySelector('i');
                if (data.liked) {
                    btn.classList.replace('btn-outline-danger', 'btn-danger');
                    icon.classList.replace('bi-heart', 'bi-heart-fill');
                } else {
                    btn.classList.replace('btn-danger', 'btn-outline-danger');
                    icon.classList.replace('bi-heart-fill', 'bi-heart');
                }
            } else if (data.status === 401 || data.message === 'Unauthenticated') {
                alert('Faça login para curtir o artigo!');
            }
        });
    }
</script>
@endpush

@push('estilos')
<style>
    .bg-gray-50 { background-color: #f9fafb; }
    .text-gray-700 { color: #374151; }
    .text-gray-600 { color: #4b5563; }
    .leading-relaxed { line-height: 1.625; }
    .object-fit-cover { object-fit: cover; }
    .x-small { font-size: 0.75rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    
    .premium-card-hover {
        transition: all 0.3s ease;
    }
    .premium-card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.1) !important;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: "/";
        color: #9ca3af;
    }
</style>
@endpush
