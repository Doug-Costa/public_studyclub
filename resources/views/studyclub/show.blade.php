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
                <div class="col-lg-9">
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
                <div class="col-lg-3 text-lg-end mt-4 mt-lg-0">
                    <div class="d-flex justify-content-lg-end gap-2">
                        <a href="#" class="btn btn-outline-primary rounded-circle p-2" title="Compartilhar no LinkedIn">
                            <i class="bi bi-linkedin fs-5"></i>
                        </a>
                        <a href="#" class="btn btn-outline-success rounded-circle p-2" title="Compartilhar no WhatsApp">
                            <i class="bi bi-whatsapp fs-5"></i>
                        </a>
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

                {{-- Botão de Download PDF --}}
                <div class="d-flex align-items-center justify-content-between p-4 bg-white rounded-4 shadow-sm">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                            <i class="bi bi-check-lg text-success"></i>
                        </div>
                        <span class="fw-semibold text-dark">Você leu a resenha completa</span>
                    </div>
                    <a href="{{ $item->external_url }}" target="_blank" class="btn btn-danger btn-lg rounded-pill px-4 fw-bold">
                        <i class="bi bi-download me-2"></i> Baixar o Artigo Completo (PDF)
                    </a>
                </div>

                {{-- Artigos Relacionados --}}
                <div class="mt-5 pt-5 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold">Outros Artigos Relacionados</h3>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px;"><i class="bi bi-chevron-left"></i></button>
                            <button class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px;"><i class="bi bi-chevron-right"></i></button>
                        </div>
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
                                                    <span><i class="bi bi-chat-fill text-primary"></i> {{ $related->comments }}</span>
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
                    {{-- Curadores --}}
                    <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Nesta Seleção</h5>
                            <div class="d-flex gap-2 mb-3">
                                <img src="https://i.pravatar.cc/150?u=1" class="rounded-circle border border-2 border-white shadow-sm" style="width: 45px; height: 45px;" alt="Curador">
                                <img src="https://i.pravatar.cc/150?u=2" class="rounded-circle border border-2 border-white shadow-sm" style="width: 45px; height: 45px; margin-left: -15px;" alt="Curador">
                                <img src="https://i.pravatar.cc/150?u=3" class="rounded-circle border border-2 border-white shadow-sm" style="width: 45px; height: 45px; margin-left: -15px;" alt="Curador">
                                <img src="https://i.pravatar.cc/150?u=4" class="rounded-circle border border-2 border-white shadow-sm" style="width: 45px; height: 45px; margin-left: -15px;" alt="Curador">
                            </div>
                            <p class="text-muted small mb-4">Jornalista e dentistas líderes selecionam artigos relevantes.</p>
                            <button class="btn btn-danger w-100 rounded-pill fw-bold py-2">Conheça os Curadores</button>
                        </div>
                    </div>

                    {{-- Categorias Populares --}}
                    <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Categorias Populares</h5>
                            @php
                                $allCategories = \App\Models\StudyClubItem::select('category', \DB::raw('count(*) as total'))->groupBy('category')->orderBy('total', 'desc')->take(5)->get();
                            @endphp
                            <div class="list-group list-group-flush border-0">
                                @foreach($allCategories as $cat)
                                    <div class="list-group-item border-0 px-0 d-flex justify-content-between align-items-center py-2">
                                        <span class="text-dark">{{ ucfirst($cat->category) }}</span>
                                        <div>
                                            <span class="text-muted small me-2">{{ rand(50, 150) }}</span> {{-- Simulação de visualizações --}}
                                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">{{ $cat->total }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Newsletter --}}
                    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('estilos')
<style>
    .bg-gray-50 { background-color: #f9fafb; }
    .text-gray-700 { color: #374151; }
    .text-gray-600 { color: #4b5563; }
    .leading-relaxed { line-height: 1.625; }
    .object-fit-cover { object-fit: cover; }
    
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
