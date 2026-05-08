@extends('layouts.master')

@section('content')
<div class="studyclub-show">
    <!-- Banner Superior -->
    <section class="sc-show-banner">
        <div class="container">
            <nav aria-label="breadcrumb" class="sc-breadcrumb-nav">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('studyclub.index') }}">StudyClub</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('studyclub.edition', $edition['number']) }}">{{ $edition['title'] }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Artigo</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Conteúdo Principal -->
    <section class="sc-article-detail">
        <div class="container">
            <div class="row">
                <!-- Coluna Principal -->
                <div class="col-lg-9">
                    <!-- Header do Artigo -->
                    <div class="sc-article-header">
                        <span class="sc-article-badge" style="background-color: #AD1457;">
                            {{ $item['category'] }}
                        </span>
                        <h1 class="sc-article-title">{{ $item['title'] }}</h1>
                        
                        <div class="sc-article-meta-bar">
                            <div class="sc-meta-left">
                                <span class="sc-author">Por {{ $item['author'] }}</span>
                                <span class="sc-separator">|</span>
                                <span class="sc-date">{{ $item['date'] }}</span>
                                <span class="sc-separator">|</span>
                                <span class="sc-source">Fonte: {{ $item['source'] }}</span>
                            </div>
                            <div class="sc-meta-social">
                                <a href="#" class="sc-social-btn sc-linkedin"><i class="bi bi-linkedin"></i></a>
                                <a href="#" class="sc-social-btn sc-twitter"><i class="bi bi-twitter"></i></a>
                                <a href="#" class="sc-social-btn sc-whatsapp"><i class="bi bi-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Layout Principal -->
                    <div class="sc-article-main">
                        <!-- Coluna da Imagem e Info -->
                        <div class="sc-article-left">
                            <div class="sc-image-box">
                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="sc-main-image">
                            </div>
                            
                            <div class="sc-info-box">
                                <div class="sc-info-item">
                                    <i class="bi bi-person"></i>
                                    <span>{{ $item['author'] }}</span>
                                </div>
                                <div class="sc-info-item">
                                    <i class="bi bi-calendar3"></i>
                                    <span>{{ $item['date'] }}</span>
                                </div>
                                <div class="sc-info-item">
                                    <i class="bi bi-journal"></i>
                                    <span>{{ $item['source'] }}</span>
                                </div>
                                
                                <div class="sc-social-share">
                                    <a href="#" class="sc-share-mini sc-linkedin-bg"><i class="bi bi-linkedin"></i></a>
                                    <a href="#" class="sc-share-mini sc-whatsapp-bg"><i class="bi bi-whatsapp"></i></a>
                                </div>
                                
                                <div class="sc-engagement">
                                    <span class="sc-likes"><i class="bi bi-heart-fill"></i> {{ $item['likes'] }} curtidas</span>
                                    <span class="sc-comments"><i class="bi bi-chat-fill"></i> {{ $item['comments'] }} comentários</span>
                                </div>
                            </div>
                        </div>

                        <!-- Coluna do Conteúdo -->
                        <div class="sc-article-right">
                            <!-- Resumo -->
                            <div class="sc-content-block">
                                <h3><i class="bi bi-file-text me-2"></i>Resumo do Artigo</h3>
                                <p>{{ $item['resumo'] }}</p>
                            </div>

                            <!-- Principais Achados -->
                            <div class="sc-content-block sc-highlight-block">
                                <h3><i class="bi bi-lightbulb me-2"></i>Principais Achados</h3>
                                <p>{{ $item['achados'] }}</p>
                            </div>

                            <!-- Implicações -->
                            <div class="sc-content-block">
                                <h3><i class="bi bi-clipboard-check me-2"></i>Implicações Clínicas</h3>
                                <p>{{ $item['implicacoes'] }}</p>
                            </div>

                            <!-- Call to Action -->
                            <div class="sc-cta-box">
                                <div class="sc-cta-left">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Você leu a resenha completa</span>
                                </div>
                                <a href="{{ $item['external_url'] }}" target="_blank" class="btn-sc-download">
                                    <i class="bi bi-download me-2"></i>
                                    Baixar o Artigo Completo (PDF)
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Artigos Relacionados -->
                    <div class="sc-related-section">
                        <div class="sc-related-header">
                            <h2>Outros Artigos Relacionados</h2>
                            <div class="sc-nav-buttons">
                                <button class="sc-nav-btn"><i class="bi bi-chevron-left"></i></button>
                                <button class="sc-nav-btn"><i class="bi bi-chevron-right"></i></button>
                            </div>
                        </div>
                        
                        <div class="sc-related-grid">
                            @foreach($relatedArticles as $related)
                            <div class="sc-related-card">
                                <div class="sc-related-image" style="background-image: url('{{ $related['image'] }}');">
                                    <span class="sc-related-category">{{ $related['category'] }}</span>
                                </div>
                                <div class="sc-related-body">
                                    <h4>{{ Str::limit($related['title'], 60) }}</h4>
                                    <div class="sc-related-footer">
                                        <div class="sc-related-stats">
                                            <span><i class="bi bi-heart-fill"></i> {{ $related['likes'] }}</span>
                                            <span><i class="bi bi-chat-fill"></i> {{ $related['comments'] }}</span>
                                        </div>
                                        <a href="{{ route('studyclub.show', [$edition['number'], $related['id']]) }}" class="btn-sc-view-related">
                                            Ver Artigo
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-3">
                    <!-- Nesta Seleção -->
                    <div class="sc-sidebar-widget">
                        <h4>Nesta Seleção</h4>
                        <div class="sc-curators-row">
                            <img src="https://i.pravatar.cc/150?u=curador1" alt="Curador" class="sc-curator-img">
                            <img src="https://i.pravatar.cc/150?u=curador2" alt="Curador" class="sc-curator-img">
                            <img src="https://i.pravatar.cc/150?u=curador3" alt="Curador" class="sc-curator-img">
                            <img src="https://i.pravatar.cc/150?u=curador4" alt="Curador" class="sc-curator-img">
                        </div>
                        <p class="sc-curators-desc">Jornalista e dentistas líderes selecionam artigos relevantes.</p>
                        <a href="#" class="btn-sc-curators">Conheça os Curadores</a>
                    </div>

                    <!-- Categorias -->
                    <div class="sc-sidebar-widget">
                        <h4>Categorias Populares</h4>
                        <ul class="sc-cat-menu">
                            <li>
                                <span>Ortodontia</span>
                                <span class="sc-count">125 <span class="sc-badge">12</span></span>
                            </li>
                            <li>
                                <span>Implantodontia</span>
                                <span class="sc-count">99 <span class="sc-badge">86</span></span>
                            </li>
                            <li>
                                <span>Endodontia</span>
                                <span class="sc-count">83 <span class="sc-badge">23</span></span>
                            </li>
                            <li>
                                <span>Periodontia</span>
                                <span class="sc-count">76 <span class="sc-badge">76</span></span>
                            </li>
                            <li>
                                <span>Estética</span>
                                <span class="sc-count">54 <span class="sc-badge">66</span></span>
                            </li>
                        </ul>
                    </div>

                    <!-- Newsletter -->
                    <div class="sc-sidebar-widget sc-newsletter-widget">
                        <h4>StudyClub Newsletter</h4>
                        <p>Receba os artigos selecionados toda quarta-feira.</p>
                        <input type="email" placeholder="Seu e-mail" class="sc-email-input">
                        <button class="btn-sc-subscribe">Assinar</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* Study Club Show Page */
.studyclub-show {
    background-color: #f8f9fa;
    min-height: 100vh;
}

/* Banner */
.sc-show-banner {
    background: linear-gradient(135deg, #AD1457 0%, #D81B60 100%);
    padding: 20px 0;
}

.sc-breadcrumb-nav .breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.sc-breadcrumb-nav .breadcrumb-item a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
}

.sc-breadcrumb-nav .breadcrumb-item.active {
    color: white;
}

.sc-breadcrumb-nav .breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255,255,255,0.5);
}

/* Article Detail */
.sc-article-detail {
    padding: 40px 0 60px;
}

.sc-article-header {
    margin-bottom: 30px;
}

.sc-article-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 4px;
    color: white;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 15px;
}

.sc-article-title {
    font-size: 2rem;
    font-weight: 800;
    color: #222;
    line-height: 1.3;
    margin-bottom: 20px;
}

.sc-article-meta-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 20px;
    border-bottom: 1px solid #e0e0e0;
}

.sc-meta-left {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.85rem;
    color: #666;
}

.sc-separator {
    color: #ccc;
}

.sc-meta-social {
    display: flex;
    gap: 8px;
}

.sc-social-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
}

.sc-linkedin { background: #0077b5; }
.sc-twitter { background: #1DA1F2; }
.sc-whatsapp { background: #25D366; }

.sc-social-btn:hover {
    transform: scale(1.1);
    color: white;
}

/* Article Main Layout */
.sc-article-main {
    display: flex;
    gap: 30px;
    margin-bottom: 40px;
}

.sc-article-left {
    width: 300px;
    flex-shrink: 0;
}

.sc-image-box {
    margin-bottom: 20px;
}

.sc-main-image {
    width: 100%;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.sc-info-box {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.sc-info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
    font-size: 0.85rem;
    color: #555;
}

.sc-info-item i {
    color: #AD1457;
    font-size: 1rem;
}

.sc-social-share {
    display: flex;
    gap: 8px;
    margin: 15px 0;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

.sc-share-mini {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    font-size: 0.85rem;
}

.sc-linkedin-bg { background: #0077b5; }
.sc-whatsapp-bg { background: #25D366; }

.sc-engagement {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
    font-size: 0.8rem;
    color: #666;
}

.sc-engagement span {
    display: block;
    margin-bottom: 8px;
}

.sc-engagement i {
    color: #D81B60;
    margin-right: 5px;
}

/* Article Right Content */
.sc-article-right {
    flex: 1;
}

.sc-content-block {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.sc-content-block h3 {
    font-size: 1rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
}

.sc-content-block h3 i {
    color: #AD1457;
}

.sc-content-block p {
    font-size: 0.95rem;
    color: #555;
    line-height: 1.7;
    margin: 0;
}

.sc-highlight-block {
    background: linear-gradient(135deg, #E8EAF6 0%, #f8f9fa 100%);
    border-left: 4px solid #AD1457;
}

/* CTA Box */
.sc-cta-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    border-radius: 12px;
    padding: 20px 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.sc-cta-left {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.9rem;
    color: #AD1457;
    font-weight: 500;
}

.sc-cta-left i {
    font-size: 1.2rem;
}

.btn-sc-download {
    padding: 12px 24px;
    background: #D81B60;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: none;
    display: inline-flex;
    align-items: center;
}

.btn-sc-download:hover {
    background: #AD1457;
    color: white;
}

/* Related Section */
.sc-related-section {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid #e0e0e0;
}

.sc-related-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.sc-related-header h2 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #222;
}

.sc-nav-buttons {
    display: flex;
    gap: 8px;
}

.sc-nav-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1px solid #ddd;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.sc-nav-btn:hover {
    background: #AD1457;
    color: white;
    border-color: #AD1457;
}

.sc-related-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.sc-related-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.sc-related-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.sc-related-image {
    height: 120px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.sc-related-category {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(255,255,255,0.9);
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #AD1457;
}

.sc-related-body {
    padding: 15px;
}

.sc-related-body h4 {
    font-size: 0.85rem;
    font-weight: 600;
    color: #222;
    margin-bottom: 15px;
    line-height: 1.4;
    height: 2.8em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.sc-related-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sc-related-stats {
    display: flex;
    gap: 10px;
    font-size: 0.7rem;
    color: #888;
}

.sc-related-stats i {
    color: #D81B60;
}

.btn-sc-view-related {
    padding: 6px 12px;
    background: transparent;
    border: 1px solid #5C6BC0;
    color: #5C6BC0;
    border-radius: 6px;
    font-size: 0.75rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-sc-view-related:hover {
    background: #5C6BC0;
    color: white;
}

/* Sidebar Widgets */
.sc-sidebar-widget {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.sc-sidebar-widget h4 {
    font-size: 0.95rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 15px;
}

.sc-curators-row {
    display: flex;
    margin-bottom: 12px;
}

.sc-curator-img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: 2px solid white;
    margin-left: -10px;
    object-fit: cover;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.sc-curator-img:first-child {
    margin-left: 0;
}

.sc-curators-desc {
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

/* Categories Menu */
.sc-cat-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sc-cat-menu li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: 0.85rem;
}

.sc-cat-menu li:last-child {
    border-bottom: none;
}

.sc-count {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #666;
}

.sc-badge {
    background: #E8EAF6;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.7rem;
    color: #5C6BC0;
}

/* Newsletter Widget */
.sc-newsletter-widget p {
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 12px;
}

.sc-email-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 10px;
    font-size: 0.85rem;
}

.btn-sc-subscribe {
    width: 100%;
    padding: 10px;
    background: #4A148C;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-sc-subscribe:hover {
    background: #6A1B9A;
}

/* Responsive */
@media (max-width: 991px) {
    .sc-article-main {
        flex-direction: column;
    }
    
    .sc-article-left {
        width: 100%;
    }
    
    .sc-related-grid {
        grid-template-columns: 1fr;
    }
    
    .sc-cta-box {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
}

@media (max-width: 576px) {
    .sc-article-title {
        font-size: 1.4rem;
    }
    
    .sc-article-meta-bar {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
}
</style>
@endsection
