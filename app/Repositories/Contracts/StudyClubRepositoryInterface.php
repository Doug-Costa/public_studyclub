<?php

namespace App\Repositories\Contracts;

use App\Models\StudyClubEdition;
use App\Models\StudyClubItem;
use Illuminate\Support\Collection;

/**
 * Interface StudyClubRepositoryInterface
 * Define o contrato para operações de persistência do Study Club
 */
interface StudyClubRepositoryInterface
{
    /**
     * Busca uma edição pelo ID
     */
    public function findEditionById(int $id): ?StudyClubEdition;

    /**
     * Busca uma edição pelo número
     */
    public function findEditionByNumber(int $number): ?StudyClubEdition;

    /**
     * Lista todas as edições ordenadas
     */
    public function findAllEditions(): Collection;

    /**
     * Lista edições ativas e publicadas
     */
    public function findPublishedEditions(): \Illuminate\Support\Collection;

    /**
     * Lista edições ativas e publicadas com paginação
     */
    public function paginatePublishedEditions(int $perPage = 6): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    /**
     * Salva uma edição
     */
    public function saveEdition(StudyClubEdition $edition): bool;

    /**
     * Deleta uma edição
     */
    public function deleteEdition(StudyClubEdition $edition): bool;

    /**
     * Busca um item pelo ID
     */
    public function findItemById(int $id): ?StudyClubItem;

    /**
     * Lista itens de uma edição
     */
    public function findItemsByEdition(int $editionId): Collection;

    /**
     * Salva um item
     */
    public function saveItem(StudyClubItem $item): bool;

    /**
     * Deleta um item
     */
    public function deleteItem(StudyClubItem $item): bool;

    /**
     * Busca item por slug único na edição
     */
    public function findItemByEditionAndId(int $editionId, string $itemId): ?StudyClubItem;
}
