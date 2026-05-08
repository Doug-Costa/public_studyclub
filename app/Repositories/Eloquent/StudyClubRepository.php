<?php

namespace App\Repositories\Eloquent;

use App\Models\StudyClubEdition;
use App\Models\StudyClubItem;
use App\Repositories\Contracts\StudyClubRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Repository: StudyClubRepository
 * Implementação Eloquent do repositório Study Club
 */
class StudyClubRepository implements StudyClubRepositoryInterface
{
    /**
     * Busca edição pelo ID com eager loading de items
     */
    public function findEditionById(int $id): ?StudyClubEdition
    {
        return StudyClubEdition::with('items')->find($id);
    }

    /**
     * Busca edição pelo número
     */
    public function findEditionByNumber(int $number): ?StudyClubEdition
    {
        return StudyClubEdition::with('items')
            ->where('number', $number)
            ->first();
    }

    /**
     * Lista todas as edições ordenadas por número decrescente
     */
    public function findAllEditions(): Collection
    {
        return StudyClubEdition::with('items')
            ->latestEditions()
            ->get();
    }

    /**
     * Lista apenas edições publicadas e ativas
     */
    public function findPublishedEditions(): \Illuminate\Support\Collection
    {
        return StudyClubEdition::with('items')
            ->active()
            ->published()
            ->latestEditions()
            ->get();
    }

    /**
     * Lista edições ativas e publicadas com paginação
     */
    public function paginatePublishedEditions(int $perPage = 6): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return StudyClubEdition::with('items')
            ->active()
            ->published()
            ->latestEditions()
            ->paginate($perPage);
    }

    /**
     * Salva edição (create ou update)
     */
    public function saveEdition(StudyClubEdition $edition): bool
    {
        return $edition->save();
    }

    /**
     * Deleta edição (cascade nos itens via migration)
     */
    public function deleteEdition(StudyClubEdition $edition): bool
    {
        return $edition->delete();
    }

    /**
     * Busca item pelo ID
     */
    public function findItemById(int $id): ?StudyClubItem
    {
        return StudyClubItem::with('edition')->find($id);
    }

    /**
     * Lista todos os itens de uma edição
     */
    public function findItemsByEdition(int $editionId): Collection
    {
        return StudyClubItem::where('edition_id', $editionId)
            ->orderBy('id')
            ->get();
    }

    /**
     * Salva item (create ou update)
     */
    public function saveItem(StudyClubItem $item): bool
    {
        return $item->save();
    }

    /**
     * Deleta item
     */
    public function deleteItem(StudyClubItem $item): bool
    {
        return $item->delete();
    }

    /**
     * Busca item específico dentro de uma edição
     * Nota: items usam ID auto-incremento, mas no frontend referenciamos por slug
     */
    public function findItemByEditionAndId(int $editionId, string $itemId): ?StudyClubItem
    {
        return StudyClubItem::where('edition_id', $editionId)
            ->where('id', $itemId)
            ->first();
    }
}
