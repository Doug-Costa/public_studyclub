<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ElasticSearchService;
use Illuminate\Support\Facades\Cache;
use Beeyev\Thumbor\Thumbor;
use Beeyev\Thumbor\Manipulations\Resize;
use Beeyev\Thumbor\Manipulations\Fit;

class BuscaElasticController extends Controller
{
    protected $elastic;
    protected $thumbor;

    public function __construct(ElasticSearchService $elastic)
    {
        $this->elastic = $elastic;
        $this->thumbor = new Thumbor('https://thumbor.dentalgo.com.br/', '8e965d636dc76246b');
    }

    /**
     * Busca geral v2
     */
    public function buscar(Request $request)
    {
        return $this->processSearch($request, 'busca.resultado', 'magazine');
    }

    /**
     * Busca facelift v2.5
     */
    public function buscar25(Request $request)
    {
        return $this->processSearch($request, 'facelift2.busca.resultado', 'magazine');
    }

    /**
     * Busca filtrada v2
     */
    public function buscarFiltrada(Request $request)
    {
        return $this->processSearchWithFilters($request, 'busca.resultado');
    }

    /**
     * Busca filtrada facelift v2.5
     */
    public function buscarFiltrada25(Request $request)
    {
        return $this->processSearchWithFilters($request, 'facelift2.busca.resultado');
    }

    /**
     * Processa a busca padrão (Boolean Search)
     */
    private function processSearch(Request $request, $view, $defaultType)
    {
        $query = trim((string)($request->input('busca', $request->input('query', $request->input('q', '')))));
        $page = (int)$request->input('page', 1);
        $size = (int)$request->input('size', 10);

        if (!$query) {
            return response()->json(['error' => 'A consulta não pode estar vazia.'], 400);
        }

        try {
            $elasticService = new ElasticSearchService();
            $result = $elasticService->searchBoolean($query, $page, $size);

            $results = $this->formatElasticResults($result);
            $colecoes = $this->getSearchCollections();
            $contadorFelipino = $this->extractAggregations($result);

            $total = (int)($result['hits']['total']['value'] ?? 0);

            if ($request->ajax()) {
                return $this->handleAjaxSearch($request, $query, $size, $page);
            }

            return view($view, [
                'query' => $query,
                'results' => $results,
                'buscaColecoes' => $colecoes,
                'contadorFelipino' => $contadorFelipino,
                'totaisPorTipo' => (array)$contadorFelipino->productTypes,
                'total' => $total,
                'page' => $page,
                'size' => $size,
                'lastPage' => (int) max(1, ceil($total / max(1, $size)))
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Processa a busca com filtros complexos
     */
    private function processSearchWithFilters(Request $request, $view)
    {
        $query = trim((string)($request->input('busca', $request->input('query', $request->input('q', '')))));
        $page = (int)$request->input('page', 1);
        $size = (int)$request->input('size', 10);
        $publishOrder = $request->input('publishOrder');
        $filters = (array)$request->input('q', []);

        if ($query === '') {
            return response()->json(['error' => 'A consulta não pode estar vazia.'], 400);
        }

        try {
            $result = $this->elastic->searchBooleanWithFilters($query, $filters, $page, $size, $publishOrder);
            $resultGlobal = $this->elastic->searchBoolean($query, 1, 0);

            $results = $this->formatElasticResults($result);
            $colecoes = $this->getSearchCollections();
            $contadorFelipino = $this->extractAggregations($resultGlobal);

            $total = (int)($result['hits']['total']['value'] ?? 0);

            if ($request->ajax()) {
                return $this->handleAjaxSearchWithFilters($request, $query, $filters, $size, $page, $publishOrder);
            }

            return view($view, [
                'query' => $query,
                'results' => $results,
                'buscaColecoes' => $colecoes,
                'contadorFelipino' => $contadorFelipino,
                'totaisPorTipo' => (array)$contadorFelipino->productTypes,
                'total' => $total,
                'page' => $page,
                'size' => $size,
                'lastPage' => (int) max(1, ceil($total / max(1, $size)))
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Formata os resultados vindos do hits->hits do Elastic
     */
    private function formatElasticResults($result)
    {
        $results = [];
        foreach (($result['hits']['hits'] ?? []) as $item) {
            $article = $item['_source'] ?? [];
            $article['cover'] = $this->getOptimizedImage($article['cover'] ?? '');
            if (isset($article['product'])) {
                $article['product']['cover'] = $this->getOptimizedImage($article['product']['cover'] ?? '');
            }
            $results[] = $article;
        }
        return $results;
    }

    /**
     * Extrai agregadores (counters) das facetas do Elastic
     */
    private function extractAggregations($result)
    {
        $aggs = $result['aggregations'] ?? [];
        $productTypesAgg = $aggs['productTypes']['buckets'] ?? [];
        $languagesAgg = $aggs['languages']['buckets'] ?? [];
        $collectionsAgg = $aggs['collections']['buckets'] ?? [];

        $totaisPorTipo = [ 'magazine' => 0, 'book' => 0, 'video' => 0 ];
        foreach ($productTypesAgg as $bucket) {
            $key = $bucket['key'] ?? '';
            if (isset($totaisPorTipo[$key])) { $totaisPorTipo[$key] = (int)$bucket['doc_count']; }
        }

        $idiomas = [ 'pt' => 0, 'en' => 0, 'es' => 0 ];
        foreach ($languagesAgg as $bucket) {
            $key = $bucket['key'] ?? '';
            if (array_key_exists($key, $idiomas)) { $idiomas[$key] = (int)$bucket['doc_count']; }
        }

        $colecoesCount = [];
        foreach ($collectionsAgg as $bucket) {
            $id = (int)($bucket['key'] ?? 0);
            if ($id) { $colecoesCount[$id] = (int)$bucket['doc_count']; }
        }

        return (object) [
            'productTypes' => (object) $totaisPorTipo,
            'collections' => $colecoesCount,
            'languages' => (object) $idiomas,
        ];
    }

    /**
     * Handle AJAX requests for scroll infinito (Standard)
     */
    private function handleAjaxSearch($request, $query, $size, $page)
    {
        $tab = (string)$request->input('tab', 'articles');
        $map = ['articles' => 'magazine', 'videos' => 'video', 'books' => 'book'];
        $productType = $map[$tab] ?? null;

        $filters = (array)$request->input('q', []);
        if ($productType) {
            $existing = (array)($filters['productTypes'] ?? []);
            $filters['productTypes'] = array_values(array_unique(array_merge($existing, [$productType])));
        }

        $ajaxResult = $this->elastic->searchBooleanWithFilters($query, $filters, $page, $size, null);
        $ajaxItems = $this->formatElasticResults($ajaxResult);

        $viewFile = (strpos($request->path(), 'facelift2') !== false) ? 'facelift2.busca._result_items' : 'busca._result_items';
        $html = view($viewFile, ['items' => $ajaxItems])->render();
        
        $totalType = (int)($ajaxResult['hits']['total']['value'] ?? 0);
        $lastPageType = (int) max(1, ceil($totalType / max(1, $size)));

        return response()->json([
            'html' => $html,
            'page' => $page,
            'lastPage' => $lastPageType,
            'hasMore' => $page < $lastPageType,
        ]);
    }

    /**
     * Handle AJAX requests for scroll infinito (Filtered)
     */
    private function handleAjaxSearchWithFilters($request, $query, $filters, $size, $page, $publishOrder)
    {
        $tab = (string)$request->input('tab', 'articles');
        $map = ['articles' => 'magazine', 'videos' => 'video', 'books' => 'book'];
        $productType = $map[$tab] ?? null;

        if ($productType) {
            $existing = (array)($filters['productTypes'] ?? []);
            $filters['productTypes'] = array_values(array_unique(array_merge($existing, [$productType])));
        }

        $ajaxResult = $this->elastic->searchBooleanWithFilters($query, $filters, $page, $size, $publishOrder);
        $ajaxItems = $this->formatElasticResults($ajaxResult);

        $viewFile = (strpos($request->path(), 'facelift2') !== false) ? 'facelift2.busca._result_items' : 'busca._result_items';
        $html = view($viewFile, ['items' => $ajaxItems])->render();
        
        $totalType = (int)($ajaxResult['hits']['total']['value'] ?? 0);
        $lastPageType = (int) max(1, ceil($totalType / max(1, $size)));

        return response()->json([
            'html' => $html,
            'page' => $page,
            'lastPage' => $lastPageType,
            'hasMore' => $page < $lastPageType,
        ]);
    }

    /**
     * Busca as coleções para o filtro de pesquisa, com cache de 1 hora.
     */
    private function getSearchCollections(): array
    {
        $token = $this->ensureToken();
        if (!$token) return [];

        $locale = session()->get('lang_code') ?: 'pt';
        $cacheKey = 'busca_colecoes_' . $locale;
        
        return Cache::remember($cacheKey, 3600, function() use ($token) {
            usleep(300000); // Pacing de 300ms
            $ch = curl_init('https://api.dentalgo.com.br/subscription/search/collections');
            $authorization = "Authorization: Bearer " . $token;
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $server_output = curl_exec($ch);
            curl_close($ch);
            $colecoes = json_decode($server_output) ?: [];

            if (is_array($colecoes)) {
                foreach ($colecoes as $value) {
                    if (is_object($value) && !empty($value->cover)) {
                        $value->cover = $this->getOptimizedImage($value->cover);
                    }
                }
            }
            return is_array($colecoes) ? $colecoes : [];
        });
    }

    /**
     * Otimiza imagens com o Thumbor
     */
    private function getOptimizedImage($imageUrl)
    {
        if (!empty($imageUrl)) {
            $this->thumbor->resize(Resize::ORIG, 450);
            $this->thumbor->fit(Fit::FIT_IN);
            $this->thumbor->imageUrl($imageUrl);
            return $this->thumbor->get();
        }
        return $imageUrl;
    }

    /**
     * Garante o tokenGlobal
     */
    private function ensureToken()
    {
        $token = Cache::get('tokenGlobal');
        if (!empty($token)) return $token;

        try {
            $pages = app(\App\Http\Controllers\PagesController::class);
            if (method_exists($pages, 'initializeToken')) {
                $pages->initializeToken();
            } elseif (method_exists($pages, 'index')) {
                $pages->index(request());
            }
        } catch (\Throwable $e) {}

        return Cache::get('tokenGlobal');
    }
}