<?php

namespace App\Http\Controllers;

use App\Models\StudyClubEdition;
use App\Repositories\Contracts\StudyClubRepositoryInterface;
use Illuminate\Http\Request;

/**
 * Controller: StudyClubController (Público)
 * Exibe as edições e itens do Study Club para visitantes
 */
class StudyClubController extends Controller
{
    private StudyClubRepositoryInterface $repository;

    public function __construct(StudyClubRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Lista todas as edições publicadas
     */
    public function index()
    {
        // Pega a edição mais recente publicada e ativa
        $latestEdition = $this->repository->findPublishedEditions()->first();
        
        // Pega as edições para o arquivo (playlist), excluindo a mais recente se ela existir
        $query = \App\Models\StudyClubEdition::active()->published()->latestEditions();
        
        if ($latestEdition) {
            $query->where('id', '!=', $latestEdition->id);
        }
        
        $editions = $query->paginate(6);

        return view('studyclub.index', compact('latestEdition', 'editions'));
    }

    /**
     * Exibe uma edição específica
     */
    public function edition(int $number)
    {
        $edition = $this->repository->findEditionByNumber($number);

        if (!$edition || !$edition->isPublished()) {
            abort(404, 'Edição não encontrada');
        }

        return view('studyclub.edition', compact('edition'));
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

        $item = $this->repository->findItemByEditionAndId($edition->id, $itemId);

        if (!$item) {
            abort(404, 'Artigo não encontrado');
        }

        // Busca artigos relacionados (outros da mesma edição)
        $relatedArticles = $edition->items
            ->where('id', '!=', $item->id)
            ->take(3);

        return view('studyclub.show', compact('edition', 'item', 'relatedArticles'));
    }
}
