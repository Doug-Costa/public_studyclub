@extends('layouts.master')

@section('content')
<div class="studyclub-page">
    <!-- Banner Principal -->
    <section class="sc-banner" style="background-image: url('{{ asset('imagens/studyclub/banner_bg.png') }}'); background-size: cover; background-position: 35% center;">
        <div class="sc-banner-overlay"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="sc-banner-content">
                        <span class="sc-badge">DentalGo StudyClub</span>
                        <h1>Artigos Selecionados da Semana</h1>
                        <p class="sc-subtitle">Uma seleção de artigos de destaque revisados pelo nosso jornalista e time de dentistas</p>
                        <a href="#edicoes" class="btn-sc-primary">
                            <i class="bi bi-collection-play me-2"></i>
                            Nova Seleção Disponível!
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="sc-banner-image">
                        <div class="sc-doctor-illustration">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Lista de Artigos -->
    <section class="sc-articles-section" id="edicoes">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-9">
                    <!-- Header -->
                    <div class="sc-section-header">
                        <div>
                            <h2>Lista de Artigos da Semana</h2>
                            <p>Artigos selecionados pelos nossos especialistas nas revistas científicas.</p>
                        </div>
                        <a href="#newsletter" class="btn-sc-newsletter">
                            Assinar Newsletter
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Category Tabs -->
                    <div class="sc-category-tabs">
                        <button class="sc-tab active">Ortodontia</button>
                        <button class="sc-tab">Implantodontia</button>
                        <button class="sc-tab">Endodontia</button>
                        <button class="sc-tab">Estética</button>
                        <button class="sc-tab-more"><i class="bi bi-chevron-right"></i></button>
                    </div>

                    <!-- Articles Grid -->
                    <div class="sc-articles-grid">
                        @foreach($editions as $editionIndex => $edition)
                            @foreach($edition['items'] as $item)
                            <div class="sc-article-card">
                                <div class="sc-card-image" style="background-image: url('{{ $item['image'] }}');">
                                    <span class="sc-card-category" style="background-color: #{{ ['AD1457', 'D81B60', '00695C', '1565C0'][$loop->index % 4] }};">
                                        {{ $item['category'] }}
                                    </span>
                                </div>
                                <div class="sc-card-body">
                                    <span class="sc-card-type">{{ $item['type_label'] }}</span>
                                    <h3>{{ $item['title'] }}</h3>
                                    <p class="sc-card-summary">{{ Str::limit($item['resumo'], 100) }}</p>
                                    
                                    <div class="sc-card-footer">
                                        <div class="sc-card-stats">
                                            <span><i class="bi bi-heart-fill"></i> {{ $item['likes'] }}</span>
                                            <span><i class="bi bi-chat-fill"></i> {{ $item['comments'] }}</span>
                                        </div>
                                        <a href="{{ route('studyclub.show', [$edition['number'], $item['id']]) }}" class="btn-sc-view">
                                            Ver Artigo
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endforeach
                    </div>

                    <!-- Editions Archive -->
                    <div class="sc-archive-section mt-5">
                        <h3 class="sc-archive-title">
                            <i class="bi bi-archive me-2"></i>
                            Arquivo de Playlists
                        </h3>
                        <div class="row">
                            @foreach($editions as $edition)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <a href="{{ route('studyclub.edition', $edition['number']) }}" class="sc-archive-card">
                                    <div class="sc-archive-info">
                                        <span class="sc-archive-number">#{{ $edition['number'] }}</span>
                                        <span class="sc-archive-date">{{ $edition['date'] }}</span>
                                    </div>
                                    <div class="sc-archive-arrow">
                                        <i class="bi bi-chevron-right"></i>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-3">
                    <!-- Nesta Seleção -->
                    <div class="sc-sidebar-box">
                        <h4>Nesta Seleção</h4>
                        <div class="sc-curators">
                            <div class="sc-curator-avatars">
                                <img src="https://i.pravatar.cc/150?u=dentist1" alt="Curador" class="sc-avatar">
                                <img src="https://i.pravatar.cc/150?u=dentist2" alt="Curador" class="sc-avatar">
                                <img src="https://i.pravatar.cc/150?u=dentist3" alt="Curador" class="sc-avatar">
                                <img src="https://i.pravatar.cc/150?u=dentist4" alt="Curador" class="sc-avatar">
                            </div>
                            <p class="sc-curators-text">Jornalista e dentistas líderes selecionam artigos relevantes.</p>
                            <a href="#" class="btn-sc-curators">Conheça os Curadores</a>
                        </div>
                    </div>

                    <!-- Próximas Playlists -->
                    <div class="sc-sidebar-box">
                        <h4>Próximas Playlists</h4>
                        <div class="sc-upcoming">
                            @foreach(array_slice($editions, 0, 3) as $edition)
                            <div class="sc-upcoming-item">
                                <span class="sc-upcoming-date">{{ $edition['date'] }}</span>
                                <span class="sc-upcoming-title">Study Club #{{ $edition['number'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Progress -->
                    <div class="sc-sidebar-box sc-progress-box">
                        <h4>Ciclo semanal</h4>
                        <div class="sc-progress-bar">
                            <div class="sc-progress-fill" style="width: 0%;"></div>
                        </div>
                        <span class="sc-progress-text">0%</span>
                    </div>

                    <!-- Categories -->
                    <div class="sc-sidebar-box">
                        <h4>Categorias Populares</h4>
                        <ul class="sc-categories-list">
                            <li>
                                <span>Ortodontia</span>
                                <span class="sc-cat-count">125 <span class="sc-cat-badge">12</span></span>
                            </li>
                            <li>
                                <span>Implantodontia</span>
                                <span class="sc-cat-count">99 <span class="sc-cat-badge">86</span></span>
                            </li>
                            <li>
                                <span>Endodontia</span>
                                <span class="sc-cat-count">83 <span class="sc-cat-badge">23</span></span>
                            </li>
                            <li>
                                <span>Periodontia</span>
                                <span class="sc-cat-count">76 <span class="sc-cat-badge">76</span></span>
                            </li>
                            <li>
                                <span>Estética</span>
                                <span class="sc-cat-count">54 <span class="sc-cat-badge">66</span></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* Study Club Page Styles */
.studyclub-page {
    background-color: #f8f9fa;
    min-height: 100vh;
}

/* Banner */
.sc-banner {
    background-color: #1a1a2e;
    padding: 60px 0;
    position: relative;
    overflow: hidden;
    min-height: 400px;
}

.sc-banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.3) 50%, transparent 100%);
    z-index: 1;
}

.sc-banner .container {
    position: relative;
    z-index: 2;
}

.sc-banner-content {
    color: white;
    position: relative;
    z-index: 2;
}

.sc-badge {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    margin-bottom: 20px;
    border: 1px solid rgba(255,255,255,0.3);
}

.sc-banner h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 15px;
    line-height: 1.2;
}

.sc-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 25px;
    max-width: 500px;
}

.btn-sc-primary {
    display: inline-flex;
    align-items: center;
    background: #D81B60;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.btn-sc-primary:hover {
    background: #AD1457;
    transform: translateY(-2px);
    color: white;
}

.sc-doctor-illustration {
    font-size: 8rem;
    color: rgba(255,255,255,0.3);
    text-align: center;
}

/* Articles Section */
.sc-articles-section {
    padding: 40px 0 60px;
}

.sc-section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 25px;
}

.sc-section-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 5px;
}

.sc-section-header p {
    color: #666;
    font-size: 0.9rem;
    margin: 0;
}

.btn-sc-newsletter {
    display: inline-flex;
    align-items: center;
    background: #D81B60;
    color: white;
    padding: 10px 18px;
    border-radius: 25px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-sc-newsletter:hover {
    background: #AD1457;
    color: white;
}

.btn-sc-newsletter i {
    margin-left: 5px;
}

/* Category Tabs */
.sc-category-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.sc-tab {
    padding: 8px 18px;
    border-radius: 20px;
    border: none;
    background: #E8EAF6;
    color: #5C6BC0;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.sc-tab:hover {
    background: #C5CAE9;
}

.sc-tab.active {
    background: #AD1457;
    color: white;
}

.sc-tab-more {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1px solid #ddd;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

/* Articles Grid */
.sc-articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.sc-article-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.sc-article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.sc-card-image {
    height: 160px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.sc-card-category {
    position: absolute;
    bottom: 10px;
    left: 10px;
    padding: 4px 10px;
    border-radius: 4px;
    color: white;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
}

.sc-card-body {
    padding: 15px;
}

.sc-card-type {
    display: block;
    font-size: 0.75rem;
    color: #D81B60;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.sc-card-body h3 {
    font-size: 0.95rem;
    font-weight: 700;
    color: #222;
    line-height: 1.4;
    margin-bottom: 10px;
    height: 2.8em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.sc-card-summary {
    font-size: 0.8rem;
    color: #666;
    line-height: 1.5;
    margin-bottom: 15px;
    height: 3.6em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

.sc-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
}

.sc-card-stats {
    display: flex;
    gap: 12px;
    font-size: 0.75rem;
    color: #888;
}

.sc-card-stats i {
    color: #D81B60;
}

.btn-sc-view {
    padding: 6px 14px;
    background: #5C6BC0;
    color: white;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-sc-view:hover {
    background: #3F51B5;
    color: white;
}

/* Archive Section */
.sc-archive-section {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.sc-archive-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}

.sc-archive-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.sc-archive-card:hover {
    background: white;
    border-color: #D81B60;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.sc-archive-info {
    display: flex;
    flex-direction: column;
}

.sc-archive-number {
    font-weight: 700;
    color: #AD1457;
    font-size: 1.1rem;
}

.sc-archive-date {
    font-size: 0.8rem;
    color: #888;
}

.sc-archive-arrow {
    width: 32px;
    height: 32px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #D81B60;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* Sidebar */
.sc-sidebar-box {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.sc-sidebar-box h4 {
    font-size: 1rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 15px;
}

/* Curators */
.sc-curator-avatars {
    display: flex;
    margin-bottom: 12px;
}

.sc-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid white;
    margin-left: -8px;
    object-fit: cover;
}

.sc-avatar:first-child {
    margin-left: 0;
}

.sc-curators-text {
    font-size: 0.8rem;
    color: #666;
    line-height: 1.5;
    margin-bottom: 15px;
}

.btn-sc-curators {
    display: block;
    width: 100%;
    padding: 10px;
    background: #D81B60;
    color: white;
    text-align: center;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-sc-curators:hover {
    background: #AD1457;
    color: white;
}

/* Upcoming */
.sc-upcoming-item {
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.sc-upcoming-item:last-child {
    border-bottom: none;
}

.sc-upcoming-date {
    display: block;
    font-size: 0.75rem;
    color: #888;
    margin-bottom: 3px;
}

.sc-upcoming-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: #AD1457;
}

/* Progress */
.sc-progress-box {
    text-align: center;
}

.sc-progress-bar {
    height: 6px;
    background: #E8EAF6;
    border-radius: 3px;
    overflow: hidden;
    margin: 10px 0;
}

.sc-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #AD1457, #D81B60);
    border-radius: 3px;
    transition: width 0.3s ease;
}

.sc-progress-text {
    font-size: 0.8rem;
    color: #666;
}

/* Categories */
.sc-categories-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sc-categories-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: 0.85rem;
}

.sc-categories-list li:last-child {
    border-bottom: none;
}

.sc-cat-count {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
}

.sc-cat-badge {
    background: #E8EAF6;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.7rem;
    color: #5C6BC0;
}

/* Responsive */
@media (max-width: 991px) {
    .sc-banner {
        padding: 40px 0;
    }
    
    .sc-banner h1 {
        font-size: 1.8rem;
    }
    
    .sc-section-header {
        flex-direction: column;
        gap: 15px;
    }
    
    .sc-articles-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
