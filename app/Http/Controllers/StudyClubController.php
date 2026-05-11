<?php

namespace App\Http\Controllers;

use App\Models\StudyClubEdition;
use App\Models\StudyClubItem;
use App\Models\StudyClubLike;
use App\Models\StudyClubComment;
use App\Repositories\Contracts\StudyClubRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudyClubController extends Controller
{
    protected $repository;

    public function __construct(StudyClubRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Landing page do Study Club
     */
    public function index()
    {
        $latestEdition = StudyClubEdition::published()
            ->active()
            ->latestEditions()
            ->first();

        $query = StudyClubEdition::published()
            ->active()
            ->latestEditions();
            
        if ($latestEdition) {
            $query->where('id', '!=', $latestEdition->id);
        }
        
        $editions = $query->paginate(6);

        // Estatísticas reais de categorias para a sidebar
        $categoriesStats = StudyClubItem::select('category')
            ->selectRaw('COUNT(*) as total_items')
            ->selectRaw('SUM(likes) as total_likes')
            ->groupBy('category')
            ->orderBy('total_items', 'desc')
            ->get();

        return view('studyclub.index', compact('latestEdition', 'editions', 'categoriesStats'));
    }

    /**
     * Exibe todos os itens de uma edição específica
     */
    public function edition(int $number)
    {
        $edition = $this->repository->findEditionByNumber($number);

        if (!$edition || !$edition->isPublished()) {
            abort(404, 'Edição não encontrada');
        }

        // Estatísticas reais de categorias para a sidebar
        $categoriesStats = StudyClubItem::select('category')
            ->selectRaw('COUNT(*) as total_items')
            ->selectRaw('SUM(likes) as total_likes')
            ->groupBy('category')
            ->orderBy('total_items', 'desc')
            ->get();

        return view('studyclub.edition', compact('edition', 'categoriesStats'));
    }

    /**
     * Exibe um item específico dentro de uma edição
     */
    public function show(int $editionNumber, int $itemId)
    {
        $edition = $this->repository->findEditionByNumber($editionNumber);

        if (!$edition || !$edition->isPublished()) {
            abort(404, 'Edição não encontrada');
        }

        $item = StudyClubItem::find($itemId);

        if (!$item || $item->edition_id != $edition->id) {
            abort(404, 'Artigo não encontrado');
        }

        // Verifica se o usuário logado já deu like
        $item->is_liked = false;
        if (session()->has('usuario')) {
            $user = session('usuario');
            $userId = isset($user->id) ? $user->id : (isset($user['id']) ? $user['id'] : null);
            if ($userId) {
                $item->is_liked = StudyClubLike::where('item_id', $item->id)
                    ->where('user_id', $userId)
                    ->exists();
            }
        }

        // Busca artigos relacionados (outros da mesma edição)
        $relatedArticles = $edition->items
            ->where('id', '!=', $item->id)
            ->take(3);

        // Estatísticas reais de categorias para a sidebar
        $categoriesStats = StudyClubItem::select('category')
            ->selectRaw('COUNT(*) as total_items')
            ->selectRaw('SUM(likes) as total_likes')
            ->groupBy('category')
            ->orderBy('total_items', 'desc')
            ->get();

        return view('studyclub.show', compact('edition', 'item', 'relatedArticles', 'categoriesStats'));
    }

    /**
     * Processa Like/Unlike (AJAX)
     */
    public function toggleLike(Request $request, $itemId)
    {
        if (!session()->has('usuario')) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $user = session('usuario');
        $userId = isset($user->id) ? $user->id : (isset($user['id']) ? $user['id'] : null);
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'User ID not found'], 400);
        }

        $item = StudyClubItem::findOrFail($itemId);
        
        $like = StudyClubLike::where('item_id', $item->id)
            ->where('user_id', $userId)
            ->first();

        if ($like) {
            $like->delete();
            $item->decrement('likes');
            $liked = false;
        } else {
            StudyClubLike::create([
                'item_id' => $item->id,
                'user_id' => $userId
            ]);
            $item->increment('likes');
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likesCount' => $item->likes
        ]);
    }
}
