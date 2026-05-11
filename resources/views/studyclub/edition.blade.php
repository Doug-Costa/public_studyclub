@extends('facelift2.master')

@section('title', $edition->title . ' - Study Club')

@section('content')
{{-- Banner Hero Premium --}}
<div class="studyclub-hero py-5 mb-5" style="background: linear-gradient(135deg, #4c1d95 0%, #7c3aed 50%, #db2777 100%); position: relative; overflow: hidden;">
    <div class="container py-4 position-relative" style="z-index: 2;">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('studyclub.index') }}" class="text-white opacity-75 text-decoration-none">StudyClub</a></li>
                <li class="breadcrumb-item active text-white fw-bold" aria-current="page">{{ $edition->title }}</li>
            </ol>
        </nav>

        <div class="row align-items-center">
            <div class="col-lg-7 text-white">
                <span class="badge rounded-pill bg-white text-dark px-3 py-2 mb-3 fw-bold">Edição #{{ $edition->number }}</span>
                <h1 class="display-3 fw-bold mb-3" style="font-family: 'Prompt', sans-serif;">{{ $edition->title }}</h1>
                <p class="lead opacity-90 mb-4 pb-2" style="max-width: 600px;">
                    <i class="bi bi-calendar3 me-2"></i>Playlist Semanal • {{ $edition->formatted_date }}
                </p>
                <div class="hero-description fs-5 opacity-75 mb-0">
                    {{ $edition->description ?? 'Alô, alô, pessoal! Prontos para os conteúdos desta semana para o nosso Study Club?! Os artigos de hoje estão simplesmente imperdíveis!' }}
                </div>
            </div>
            {{-- Espaço para imagem da doutora (se houver no futuro) --}}
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="hero-badge-overlay p-4 d-inline-block bg-white bg-opacity-10 rounded-4 backdrop-blur">
                    <p class="mb-0 text-white fw-semibold">Artigos nesta edição: <strong>{{ $edition->items->count() }}</strong></p>
                    <div class="mt-2 h-1 bg-danger rounded-pill" style="width: 60px; height: 4px;"></div>
                </div>
            </div>
        </div>
    </div>
    {{-- Elementos decorativos --}}
    <div class="position-absolute top-0 end-0 opacity-10" style="transform: translate(20%, -20%);">
        <i class="bi bi-journal-text" style="font-size: 20rem; color: white;"></i>
    </div>
</div>

<div class="container pb-5">
    {{-- Filtros de Categorias --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex align-items-center gap-3 overflow-auto pb-2 category-filters">
                <button class="btn btn-primary rounded-pill px-4 filter-active">Todas</button>
                @php 
                    $categories = $edition->items->pluck('category')->unique();
                @endphp
                @foreach($categories as $cat)
                    <button class="btn btn-outline-secondary rounded-pill px-4">{{ strtoupper($cat) }}</button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <h2 class="h3 fw-bold mb-4" style="color: #1f2937;">Artigos da Semana</h2>
            <p class="text-muted mb-5">Selecionados pelo jornalista e nosso time de dentistas especialistas.</p>

            {{-- Lista de Artigos --}}
            <div class="studyclub-list">
                @forelse($edition->items as $index => $item)
                    <div class="studyclub-item-card bg-white rounded-4 shadow-sm border-0 mb-4 p-4 position-relative overflow-hidden">
                        <div class="row g-4 align-items-center">
                            {{-- Número do Item --}}
                            <div class="item-number position-absolute top-0 start-0 m-3 d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold shadow-sm" style="width: 32px; height: 32px; z-index: 10;">
                                {{ $index + 1 }}
                            </div>

                            <div class="col-md-4">
                                <div class="card-img-wrapper rounded-3 overflow-hidden shadow-sm" style="height: 200px;">
                                    <img src="{{ $item->image_url }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->title }}">
                                    <div class="category-overlay position-absolute bottom-0 start-0 m-2">
                                        <span class="badge bg-primary px-3 py-2">{{ $item->formatted_category }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="ps-md-2">
                                    <span class="text-danger fw-bold text-uppercase small mb-2 d-block">{{ $item->type_label }}</span>
                                    <h3 class="h4 fw-bold mb-2 text-dark">{{ $item->title }}</h3>
                                    <p class="text-muted small mb-3">Por: {{ $item->author }}</p>
                                    <p class="text-gray-600 mb-4">{{ Str::limit($item->resumo, 180) }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            <span class="me-3"><i class="bi bi-heart-fill text-danger me-1"></i>{{ $item->likes }}</span>
                                        </div>
                                        <a href="{{ route('studyclub.show', [$edition->number, $item->id]) }}" class="btn btn-danger rounded-pill px-4 fw-bold">
                                            Ler Resenha <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 bg-light rounded-4">
                        <i class="bi bi-journal-x display-4 text-muted"></i>
                        <p class="mt-3 text-muted">Nenhum artigo nesta edição.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Sidebar Premium --}}
        <div class="col-lg-4 mt-5 mt-lg-0">
            <div class="sticky-top" style="top: 100px;">
                {{-- Sidebar Categorias --}}
                @include('studyclub.partials._sidebar_categories')

                {{-- Próximas Playlists --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Próximas Playlists</h5>
                        @php
                            $upcoming = \App\Models\StudyClubEdition::where('number', '>', $edition->number)->published()->active()->orderBy('number', 'asc')->take(2)->get();
                        @endphp
                        <div class="upcoming-list">
                            @forelse($upcoming as $up)
                                <div class="upcoming-item p-3 rounded-3 bg-light mb-2">
                                    <small class="text-muted d-block">{{ $up->formatted_date }}</small>
                                    <a href="{{ route('studyclub.edition', $up->number) }}" class="text-decoration-none fw-bold text-dark hover-purple">{{ $up->title }}</a>
                                </div>
                            @empty
                                <p class="text-muted small">Novidades em breve!</p>
                            @endforelse
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
    
    .studyclub-hero {
        border-bottom-left-radius: 40px;
        border-bottom-right-radius: 40px;
    }
    .backdrop-blur {
        backdrop-filter: blur(8px);
    }
    .category-filters::-webkit-scrollbar {
        display: none;
    }
    .filter-active {
        background-color: #4c1d95 !important;
        border-color: #4c1d95 !important;
    }
    .studyclub-item-card {
        transition: all 0.3s ease;
    }
    .studyclub-item-card:hover {
        transform: scale(1.01);
        box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
    }
    .hover-purple:hover {
        color: #7c3aed !important;
    }
    .object-fit-cover {
        object-fit: cover;
    }
    .x-small { font-size: 0.75rem; }
</style>
@endpush
