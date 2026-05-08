@extends('layouts.master')

@section('content')
<div class="studyclub-edition">
    <!-- Banner da Edição -->
    <section class="sc-edition-banner">
        <div class="container">
            <nav aria-label="breadcrumb" class="sc-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('studyclub.index') }}">StudyClub</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $edition['title'] }}</li>
                </ol>
            </nav>
            
            <div class="sc-edition-header">
                <span class="sc-edition-badge">Edição {{ $edition['number'] }}</span>
                <h1>{{ $edition['title'] }}</h1>
                <p class="sc-edition-date">
                    <i class="bi bi-calendar3"></i> 
                    Playlist Semanal • {{ $edition['date'] }}
                </p>
                <p class="sc-edition-description">{{ $edition['description'] }}</p>
                
                <div class="sc-edition-progress">
                    <span>Artigos nesta edição: {{ count($edition['items']) }}</span>
                    <div class="sc-progress-mini">
                        <div class="sc-progress-mini-fill" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sc-banner-gradient"></div>
    </section>

    <!-- Lista de Artigos -->
    <section class="sc-edition-articles">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-9">
                    <div class="sc-articles-header">
                        <h2>Artigos da Semana</h2>
                        <p>Selecionados pelo jornalista e nosso time de dentistas</p>
                    </div>

                    <!-- Articles List -->
                    <div class="sc-articles-list">
                        @foreach($edition['items'] as $index => $item)
                        <div class="sc-article-item">
                            <div class="sc-article-number">{{ $index + 1 }}</div>
                            <div class="sc-article-content">
                                <div class="sc-article-image" style="background-image: url('{{ $item['image'] }}');">
                                    <span class="sc-article-category" style="background-color: #{{ ['AD1457', 'D81B60', '00695C', '1565C0'][$index % 4] }};">
                                        {{ $item['category'] }}
                                    </span>
                                </div>
                                <div class="sc-article-info">
                                    <span class="sc-article-type">{{ $item['type_label'] }}</span>
                                    <h3>{{ $item['title'] }}</h3>
                                    <p class="sc-article-author">Por: {{ $item['author'] }}</p>
                                    <p class="sc-article-resumo">{{ $item['resumo'] }}</p>
                                    
                                    <div class="sc-article-meta">
                                        <div class="sc-article-stats">
                                            <span><i class="bi bi-heart-fill"></i> {{ $item['likes'] }}</span>
                                            <span><i class="bi bi-chat-fill"></i> {{ $item['comments'] }}</span>
                                        </div>
                                        <a href="{{ route('studyclub.show', [$edition['number'], $item['id']]) }}" class="btn-sc-read">
                                            <span>Ler Resenha</span>
                                            <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Editorial Insight -->
                    <div class="sc-editorial-box">
                        <div class="sc-editorial-avatars">
                            <img src="https://i.pravatar.cc/150?u=mauren" alt="Editorial" class="sc-editor-avatar">
                            <img src="https://i.pravatar.cc/150?u=dentist5" alt="Editorial" class="sc-editor-avatar">
                        </div>
                        <div class="sc-editorial-content">
                            <h4>Editorial Insight</h4>
                            <p>Lead jornalista, Maureni Dentori e lead dentists pelo atafarcous sobszidos e Nosso time de Dentistas. e que relecomobina um anio crítico esta semana, porcrci que pronto e herrorttortatorial que semancia seleção da semancia da zinumentia esta semana.</p>
                        </div>
                    </div>

                    <!-- Share -->
                    <div class="sc-share-box">
                        <h4>Gostou desta curadoria?</h4>
                        <p>Compartilhe com seus colegas e ajude a fortalecer a prática baseada em evidências.</p>
                        <div class="sc-share-buttons">
                            <button class="sc-btn-share sc-btn-whatsapp">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </button>
                            <button class="sc-btn-share sc-btn-link">
                                <i class="bi bi-link-45deg"></i> Copiar Link
                            </button>
                            <button class="sc-btn-share sc-btn-linkedin">
                                <i class="bi bi-linkedin"></i>
                            </button>
                            <button class="sc-btn-share sc-btn-twitter">
                                <i class="bi bi-twitter"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-3">
                    <!-- Ciclo Semanal -->
                    <div class="sc-sidebar-card sc-progress-widget">
                        <h4>Ciclo semanal:</h4>
                        <div class="sc-progress-circle">
                            <svg viewBox="0 0 36 36" class="sc-circular-chart">
                                <path class="sc-circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <path class="sc-circle" stroke-dasharray="0, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            </svg>
                            <span class="sc-progress-percent">0%</span>
                        </div>
                        <p class="sc-progress-label">Comece a leitura</p>
                    </div>

                    <!-- Próximas Playlists -->
                    <div class="sc-sidebar-card">
                        <h4>Próximas Playlists</h4>
                        <div class="sc-playlist-list">
                            <div class="sc-playlist-item">
                                <span class="sc-playlist-date">{{ $edition['date'] }}</span>
                                <span class="sc-playlist-name">Study Club #{{ $edition['number'] + 1 }}</span>
                            </div>
                            <div class="sc-playlist-item">
                                <span class="sc-playlist-date">Próxima semana</span>
                                <span class="sc-playlist-name">Study Club #{{ $edition['number'] + 2 }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Assine -->
                    <div class="sc-sidebar-card sc-cta-card">
                        <div class="sc-cta-icon">
                            <i class="bi bi-bookmark-star-fill"></i>
                        </div>
                        <h4>Assine já para Acesso Completo</h4>
                        <p>Tenha acesso a todo o conteúdo do DentalGo StudyClub e mais de 6 mil artigos científicos.</p>
                        <a href="/assine" class="btn-sc-assine">Assine Já</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* Study Club Edition Page */
.studyclub-edition {
    background-color: #f8f9fa;
    min-height: 100vh;
}

/* Banner */
.sc-edition-banner {
    background: linear-gradient(135deg, #4A148C 0%, #6A1B9A 50%, #7B1FA2 100%);
    padding: 40px 0 60px;
    position: relative;
    overflow: hidden;
}

.sc-banner-gradient {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(to top, #f8f9fa, transparent);
}

.sc-breadcrumb {
    margin-bottom: 30px;
}

.sc-breadcrumb .breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.sc-breadcrumb .breadcrumb-item a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
}

.sc-breadcrumb .breadcrumb-item.active {
    color: white;
}

.sc-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255,255,255,0.5);
}

.sc-edition-header {
    color: white;
    position: relative;
    z-index: 2;
}

.sc-edition-badge {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    margin-bottom: 15px;
    border: 1px solid rgba(255,255,255,0.3);
}

.sc-edition-header h1 {
    font-size: 2.2rem;
    font-weight: 800;
    margin-bottom: 10px;
}

.sc-edition-date {
    opacity: 0.9;
    margin-bottom: 15px;
    font-size: 0.95rem;
}

.sc-edition-description {
    font-size: 1.1rem;
    opacity: 0.9;
    max-width: 600px;
    line-height: 1.6;
}

.sc-edition-progress {
    margin-top: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 0.85rem;
}

.sc-progress-mini {
    width: 100px;
    height: 4px;
    background: rgba(255,255,255,0.3);
    border-radius: 2px;
    overflow: hidden;
}

.sc-progress-mini-fill {
    height: 100%;
    background: #D81B60;
    border-radius: 2px;
}

/* Articles Section */
.sc-edition-articles {
    padding: 40px 0 60px;
}

.sc-articles-header {
    margin-bottom: 30px;
}

.sc-articles-header h2 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 5px;
}

.sc-articles-header p {
    color: #666;
    font-size: 0.9rem;
}

/* Article Items */
.sc-articles-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 30px;
}

.sc-article-item {
    display: flex;
    gap: 15px;
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.sc-article-number {
    width: 40px;
    height: 40px;
    background: #D81B60;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    flex-shrink: 0;
}

.sc-article-content {
    display: flex;
    gap: 20px;
    flex: 1;
}

.sc-article-image {
    width: 200px;
    height: 140px;
    background-size: cover;
    background-position: center;
    border-radius: 8px;
    position: relative;
    flex-shrink: 0;
}

.sc-article-category {
    position: absolute;
    bottom: 10px;
    left: 10px;
    padding: 4px 10px;
    border-radius: 4px;
    color: white;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
}

.sc-article-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.sc-article-type {
    font-size: 0.75rem;
    color: #D81B60;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.sc-article-info h3 {
    font-size: 1rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 8px;
    line-height: 1.4;
}

.sc-article-author {
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 8px;
}

.sc-article-resumo {
    font-size: 0.85rem;
    color: #555;
    line-height: 1.5;
    margin-bottom: 12px;
    flex: 1;
}

.sc-article-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sc-article-stats {
    display: flex;
    gap: 15px;
    font-size: 0.75rem;
    color: #888;
}

.sc-article-stats i {
    color: #D81B60;
}

.btn-sc-read {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: #D81B60;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-sc-read:hover {
    background: #AD1457;
    color: white;
}

/* Editorial Box */
.sc-editorial-box {
    background: white;
    border-radius: 12px;
    padding: 25px;
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.sc-editorial-avatars {
    display: flex;
}

.sc-editor-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 3px solid white;
    margin-left: -15px;
    object-fit: cover;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.sc-editor-avatar:first-child {
    margin-left: 0;
}

.sc-editorial-content h4 {
    font-size: 1rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 10px;
}

.sc-editorial-content p {
    font-size: 0.85rem;
    color: #666;
    line-height: 1.6;
    margin: 0;
}

/* Share Box */
.sc-share-box {
    background: white;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.sc-share-box h4 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 8px;
}

.sc-share-box p {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 20px;
}

.sc-share-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
}

.sc-btn-share {
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.sc-btn-whatsapp {
    background: #25D366;
    color: white;
}

.sc-btn-link {
    background: #f0f0f0;
    color: #333;
}

.sc-btn-linkedin {
    background: #0077b5;
    color: white;
    padding: 10px 14px;
}

.sc-btn-twitter {
    background: #AD1457;
    color: white;
    padding: 10px 14px;
}

/* Sidebar */
.sc-sidebar-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.sc-sidebar-card h4 {
    font-size: 0.95rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 15px;
}

/* Progress Widget */
.sc-progress-widget {
    text-align: center;
}

.sc-progress-circle {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 15px auto;
}

.sc-circular-chart {
    display: block;
    margin: 0 auto;
    max-width: 100%;
    max-height: 250px;
}

.sc-circle-bg {
    fill: none;
    stroke: #E8EAF6;
    stroke-width: 3;
}

.sc-circle {
    fill: none;
    stroke: #D81B60;
    stroke-width: 3;
    stroke-linecap: round;
    animation: progress 1s ease-out forwards;
}

@keyframes progress {
    0% { stroke-dasharray: 0 100; }
}

.sc-progress-percent {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.2rem;
    font-weight: 700;
    color: #AD1457;
}

.sc-progress-label {
    font-size: 0.8rem;
    color: #666;
}

/* Playlist List */
.sc-playlist-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.sc-playlist-item {
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
}

.sc-playlist-date {
    display: block;
    font-size: 0.7rem;
    color: #888;
    margin-bottom: 3px;
}

.sc-playlist-name {
    font-size: 0.85rem;
    font-weight: 600;
    color: #AD1457;
}

/* CTA Card */
.sc-cta-card {
    text-align: center;
    background: linear-gradient(135deg, #AD1457 0%, #D81B60 100%);
    color: white;
}

.sc-cta-icon {
    font-size: 2.5rem;
    color: #AD1457;
    margin-bottom: 10px;
}

.sc-cta-card h4 {
    color: white;
    margin-bottom: 10px;
}

.sc-cta-card p {
    font-size: 0.8rem;
    opacity: 0.9;
    margin-bottom: 15px;
}

.btn-sc-assine {
    display: block;
    width: 100%;
    padding: 12px;
    background: #D81B60;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-sc-assine:hover {
    background: #AD1457;
    color: white;
}

/* Responsive */
@media (max-width: 991px) {
    .sc-edition-header h1 {
        font-size: 1.6rem;
    }
    
    .sc-article-content {
        flex-direction: column;
    }
    
    .sc-article-image {
        width: 100%;
        height: 180px;
    }
    
    .sc-editorial-box {
        flex-direction: column;
        text-align: center;
    }
    
    .sc-editorial-avatars {
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .sc-article-item {
        flex-direction: column;
        gap: 10px;
    }
    
    .sc-article-number {
        align-self: flex-start;
    }
}
</style>
@endsection
