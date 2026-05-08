@extends('facelift2.master')

@section('title', 'Study Club - Dental GO')

@section('content')
<div class="studyclub-landing">
    {{-- Hero Premium --}}
    <div class="hero-section position-relative mb-5" style="background: url('{{ asset('imagens/studyclub/studyclub_hero.png') }}') no-repeat center center; background-size: cover; min-height: 450px;">
        <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(90deg, rgba(76, 29, 149, 0.9) 0%, rgba(124, 58, 237, 0.4) 100%);"></div>
        <div class="container position-relative py-5" style="z-index: 2;">
            <div class="row align-items-center" style="min-height: 350px;">
                <div class="col-lg-7 text-white">
                    <span class="badge rounded-pill bg-white bg-opacity-20 text-white px-3 py-2 mb-3 border border-white border-opacity-25">DentalGo StudyClub</span>
                    <h1 class="display-3 fw-bold mb-3" style="font-family: 'Prompt', sans-serif;">Artigos Selecionados da Semana</h1>
                    <p class="lead mb-4 pb-2 fs-4 opacity-90" style="max-width: 600px;">
                        Uma seleção de artigos de destaque revisados pelo nosso jornalista e time de dentistas.
                    </p>
                    <a href="#artigos-da-semana" class="btn btn-danger btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg transform-hover">
                        <i class="bi bi-play-circle me-2"></i> Nova Seleção Disponível!
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row g-5">
            {{-- Coluna Esquerda: Artigos da Edição Atual --}}
            <div class="col-lg-8" id="artigos-da-semana">
                @if($latestEdition)
                    <div class="d-flex justify-content-between align-items-end mb-4">
                        <div>
                            <h2 class="h3 fw-bold mb-1" style="color: #1f2937;">Lista de Artigos da Semana</h2>
                            <p class="text-muted mb-0 small">Artigos selecionados pelos nossos especialistas nas revistas científicas.</p>
                        </div>
                        <a href="#" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">Assinar Newsletter <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>

                    {{-- Filtros Rápidos --}}
                    <div class="d-flex gap-2 mb-4 overflow-auto pb-2 scrollbar-hidden">
                        <button class="btn btn-primary btn-sm rounded-pill px-3 filter-pill active">Ortodontia</button>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 filter-pill">Implantodontia</button>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 filter-pill">Endodontia</button>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 filter-pill">Estética</button>
                        <button class="btn btn-outline-secondary btn-sm rounded-circle p-1" style="width: 28px; height: 28px;"><i class="bi bi-chevron-right"></i></button>
                    </div>

                    {{-- Grid de Artigos --}}
                    <div class="row g-4 mb-5">
                        @forelse($latestEdition->items as $item)
                            <div class="col-md-6">
                                <a href="{{ route('studyclub.show', [$latestEdition->number, $item->id]) }}" class="text-decoration-none">
                                    <div class="card border-0 rounded-4 shadow-sm h-100 premium-card-hover overflow-hidden">
                                        <div class="position-relative" style="height: 180px;">
                                            <img src="{{ $item->image_url }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->title }}">
                                            <span class="badge bg-primary position-absolute bottom-0 start-0 m-3">{{ $item->formatted_category }}</span>
                                        </div>
                                        <div class="card-body p-4">
                                            <span class="text-danger fw-bold text-uppercase x-small mb-1 d-block">{{ $item->type_label }}</span>
                                            <h5 class="fw-bold text-dark mb-2" style="font-size: 1.1rem; min-height: 2.4em;">{{ Str::limit($item->title, 65) }}</h5>
                                            <p class="text-muted x-small mb-3">Por: {{ $item->author }}</p>
                                            <p class="text-gray-600 small mb-4 line-clamp-3">
                                                {{ Str::limit($item->resumo, 120) }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-muted small">
                                                    <span class="me-2"><i class="bi bi-heart-fill text-danger"></i> {{ $item->likes }}</span>
                                                    <span><i class="bi bi-chat-fill text-primary"></i> {{ $item->comments }}</span>
                                                </div>
                                                <span class="btn btn-outline-primary btn-sm rounded-pill px-3">Ver Artigo</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <p class="text-muted">Nenhum artigo nesta edição.</p>
                            </div>
                        @endforelse
                    </div>
                @else
                    <div class="text-center py-5 bg-light rounded-4 mb-5">
                        <i class="bi bi-journal-x display-4 text-muted"></i>
                        <p class="mt-3 text-muted">Nenhuma edição disponível no momento.</p>
                    </div>
                @endif

                {{-- Seção: Outras Playlists (O Arquivo) --}}
                @if(!$editions->isEmpty())
                    <div class="mt-5 pt-5 border-top">
                        <h3 class="fw-bold mb-4">Arquivo de Playlists</h3>
                        <div class="row g-4">
                            @foreach($editions as $edition)
                                <div class="col-md-6">
                                    <a href="{{ route('studyclub.edition', $edition->number) }}" class="text-decoration-none">
                                        <div class="card border-0 rounded-4 bg-light p-3 h-100 archive-card">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                                                    <i class="bi bi-collection-play-fill fs-4"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">{{ $edition->formatted_date }}</small>
                                                    <h5 class="fw-bold text-dark mb-0">Study Club #{{ $edition->number }}</h5>
                                                    <p class="text-muted small mb-0">{{ Str::limit($edition->title, 40) }}</p>
                                                </div>
                                                <i class="bi bi-chevron-right ms-auto text-muted"></i>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        {{-- Paginação do Arquivo --}}
                        <div class="d-flex justify-content-center mt-5">
                            {{ $editions->links() }}
                        </div>
                    </div>
                @endif
            </div>

            {{-- Coluna Direita: Sidebar --}}
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px;">
                    {{-- Curadores --}}
                    <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3 small text-uppercase tracking-wider">Nesta Seleção</h5>
                            <div class="d-flex gap-1 mb-3">
                                <img src="https://i.pravatar.cc/150?u=1" class="rounded-circle border border-2 border-white shadow-sm" style="width: 38px; height: 38px;" alt="Curador">
                                <img src="https://i.pravatar.cc/150?u=2" class="rounded-circle border border-2 border-white shadow-sm" style="width: 38px; height: 38px; margin-left: -12px;" alt="Curador">
                                <img src="https://i.pravatar.cc/150?u=3" class="rounded-circle border border-2 border-white shadow-sm" style="width: 38px; height: 38px; margin-left: -12px;" alt="Curador">
                                <img src="https://i.pravatar.cc/150?u=4" class="rounded-circle border border-2 border-white shadow-sm" style="width: 38px; height: 38px; margin-left: -12px;" alt="Curador">
                            </div>
                            <p class="text-muted x-small mb-4">Jornalista e dentistas líderes selecionam artigos relevantes.</p>
                            <button class="btn btn-danger w-100 rounded-pill fw-bold btn-sm py-2">Conheça os Curadores</button>
                        </div>
                    </div>

                    {{-- Próximas Playlists (Simulado ou Real) --}}
                    <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3 small text-uppercase tracking-wider">Próximas Playlists</h5>
                            <div class="list-group list-group-flush border-0">
                                <div class="list-group-item border-0 px-0 py-2">
                                    <small class="text-muted d-block">28/05/2026</small>
                                    <span class="fw-bold small">Study Club #10</span>
                                </div>
                                <div class="list-group-item border-0 px-0 py-2">
                                    <small class="text-muted d-block">04/06/2026</small>
                                    <span class="fw-bold small">Study Club #11</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ciclo Semanal --}}
                    <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white">
                        <div class="card-body p-4 text-center">
                            <h5 class="fw-bold mb-3 small text-uppercase tracking-wider">Ciclo semanal</h5>
                            <div class="progress mb-2" style="height: 6px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="x-small text-muted">0%</span>
                        </div>
                    </div>

                    {{-- Categorias Populares --}}
                    <div class="card border-0 rounded-4 shadow-sm bg-white">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3 small text-uppercase tracking-wider">Categorias Populares</h5>
                            <div class="d-flex flex-column gap-2">
                                @php
                                    $popCategories = \App\Models\StudyClubItem::select('category', \DB::raw('count(*) as total'))->groupBy('category')->orderBy('total', 'desc')->take(5)->get();
                                @endphp
                                @foreach($popCategories as $cat)
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small text-dark">{{ ucfirst($cat->category) }}</span>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-muted x-small">{{ rand(50, 150) }}</span>
                                            <span class="badge bg-light text-muted rounded-pill x-small">{{ $cat->total }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
    @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@400;600;700&display=swap');
    
    .x-small { font-size: 0.75rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .object-fit-cover { object-fit: cover; }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .hero-section {
        border-bottom-left-radius: 40px;
        border-bottom-right-radius: 40px;
        overflow: hidden;
    }
    
    .premium-card-hover {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .premium-card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.1) !important;
    }
    
    .archive-card {
        transition: background-color 0.2s;
    }
    .archive-card:hover {
        background-color: #f3f4f6 !important;
    }
    
    .filter-pill.active {
        background-color: #4c1d95 !important;
        border-color: #4c1d95 !important;
    }
    
    .scrollbar-hidden::-webkit-scrollbar {
        display: none;
    }
    
    .transform-hover:hover {
        transform: translateY(-2px);
    }
</style>
@endpush
