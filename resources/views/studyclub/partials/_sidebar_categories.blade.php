{{-- Categorias Populares --}}
<div class="card border-0 rounded-4 shadow-sm bg-white mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3 small text-uppercase tracking-wider">Categorias Populares</h5>
        
        <div id="categories-pagination-container">
            @php
                $chunks = $categoriesStats->chunk(5);
            @endphp
            
            @foreach($chunks as $index => $chunk)
                <div class="category-page" id="cat-page-{{ $index }}" style="display: {{ $index === 0 ? 'flex' : 'none' }}; flex-direction: column; gap: 0.75rem;">
                    @foreach($chunk as $cat)
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-dark fw-medium">{{ strtoupper($cat->category) }}</span>
                            <div class="d-flex align-items-center gap-3">
                                {{-- Likes --}}
                                <div class="d-flex align-items-center text-muted x-small" title="Total de curtidas">
                                    <i class="bi bi-heart-fill text-danger me-1" style="font-size: 0.65rem;"></i>
                                    <span>{{ $cat->total_likes ?? 0 }}</span>
                                </div>
                                {{-- Artigos --}}
                                <div class="d-flex align-items-center" title="Quantidade de artigos">
                                    <i class="bi bi-journal-text text-primary me-1" style="font-size: 0.75rem;"></i>
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill x-small" style="min-width: 22px;">{{ $cat->total_items }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        @if($chunks->count() > 1)
            <div class="d-flex justify-content-center align-items-center gap-3 mt-4 border-top pt-3">
                <button class="btn btn-outline-secondary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" 
                        style="width: 28px; height: 28px;" onclick="changeCatPage(-1)">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <span class="x-small text-muted fw-bold" id="cat-page-indicator">1 / {{ $chunks->count() }}</span>
                <button class="btn btn-outline-secondary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" 
                        style="width: 28px; height: 28px;" onclick="changeCatPage(1)">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        @endif
    </div>
</div>

<script>
    let currentCatPage = 0;
    const totalCatPages = {{ $chunks->count() }};

    function changeCatPage(direction) {
        const pages = document.querySelectorAll('.category-page');
        if (pages.length === 0) return;
        
        pages[currentCatPage].style.display = 'none';
        currentCatPage += direction;
        
        if (currentCatPage >= totalCatPages) currentCatPage = 0;
        if (currentCatPage < 0) currentCatPage = totalCatPages - 1;
        
        pages[currentCatPage].style.display = 'flex';
        document.getElementById('cat-page-indicator').innerText = `${currentCatPage + 1} / ${totalCatPages}`;
    }
</script>
