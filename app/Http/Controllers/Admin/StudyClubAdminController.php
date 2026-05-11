<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudyClub\Admin\StoreEditionRequest;
use App\Http\Requests\StudyClub\Admin\StoreItemRequest;
use App\Http\Requests\StudyClub\Admin\UpdateEditionRequest;
use App\Models\StudyClubEdition;
use App\Models\StudyClubItem;
use App\Repositories\Contracts\StudyClubRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StudyClubAdminController extends Controller
{
    private StudyClubRepositoryInterface $repository;

    public function __construct(StudyClubRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Dashboard: Lista todas as edições
     */
    public function index()
    {
        $editions = $this->repository->findAllEditions();

        return view('admin.studyclub.index', compact('editions'));
    }

    /**
     * Formulário de criação de edição
     */
    public function create()
    {
        // Sugere próximo número baseado no maior existente
        $lastEdition = StudyClubEdition::orderBy('number', 'desc')->first();
        $nextNumber = $lastEdition ? $lastEdition->number + 1 : 1;

        return view('admin.studyclub.create', compact('nextNumber'));
    }

    /**
     * Salva nova edição
     */
    public function store(StoreEditionRequest $request)
    {
        try {
            $edition = new StudyClubEdition($request->validated());
            $this->repository->saveEdition($edition);

            Log::info('Study Club Edition criada', ['number' => $edition->number, 'user' => auth()->id()]);

            return redirect()
                ->route('admin.studyclub.index')
                ->with('success', "Edição #{$edition->number} criada com sucesso!");
        } catch (\Exception $e) {
            Log::error('Erro ao criar Study Club Edition', ['error' => $e->getMessage()]);

            return back()
                ->with('error', 'Erro ao criar edição. Tente novamente.')
                ->withInput();
        }
    }

    /**
     * Formulário de edição
     */
    public function edit(int $id)
    {
        $edition = $this->repository->findEditionById($id);

        if (!$edition) {
            abort(404, 'Edição não encontrada');
        }

        return view('admin.studyclub.edit', compact('edition'));
    }

    /**
     * Atualiza edição
     */
    public function update(UpdateEditionRequest $request, int $id)
    {
        try {
            $edition = $this->repository->findEditionById($id);

            if (!$edition) {
                abort(404, 'Edição não encontrada');
            }

            $edition->fill($request->validated());
            $this->repository->saveEdition($edition);

            Log::info('Study Club Edition atualizada', ['id' => $id, 'user' => auth()->id()]);

            return redirect()
                ->route('admin.studyclub.index')
                ->with('success', "Edição #{$edition->number} atualizada com sucesso!");
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar Study Club Edition', ['id' => $id, 'error' => $e->getMessage()]);

            return back()
                ->with('error', 'Erro ao atualizar edição. Tente novamente.')
                ->withInput();
        }
    }

    /**
     * Remove edição (e todos os itens por cascade)
     */
    public function destroy(int $id)
    {
        try {
            $edition = $this->repository->findEditionById($id);

            if (!$edition) {
                abort(404, 'Edição não encontrada');
            }

            // Deleta imagens dos itens antes de remover
            foreach ($edition->items as $item) {
                if ($item->image_path && Storage::disk('public')->exists($item->image_path)) {
                    Storage::disk('public')->delete($item->image_path);
                }
            }

            $this->repository->deleteEdition($edition);

            Log::info('Study Club Edition deletada', ['id' => $id, 'user' => auth()->id()]);

            return redirect()
                ->route('admin.studyclub.index')
                ->with('success', 'Edição removida com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao deletar Study Club Edition', ['id' => $id, 'error' => $e->getMessage()]);

            return back()->with('error', 'Erro ao remover edição. Tente novamente.');
        }
    }

    /**
     * Mostra formulário para adicionar item (página separada, evita problemas de modal)
     */
    public function createItem(int $editionId)
    {
        $edition = $this->repository->findEditionById($editionId);

        if (!$edition) {
            abort(404, 'Edição não encontrada');
        }

        return view('admin.studyclub.items.create', compact('edition'));
    }

    /**
     * Adiciona item a uma edição
     */
    public function storeItem(StoreItemRequest $request, int $editionId)
    {
        try {
            $edition = $this->repository->findEditionById($editionId);

            if (!$edition) {
                abort(404, 'Edição não encontrada');
            }

            $data = $request->validated();

            // Processa upload de imagem
            if ($request->hasFile('image')) {
                $data['image_path'] = $this->uploadImage($request->file('image'));
            }

            $data['edition_id'] = $editionId;

            $item = new StudyClubItem($data);
            $this->repository->saveItem($item);

            Log::info('Study Club Item criado', ['edition_id' => $editionId, 'item_id' => $item->id]);

            return redirect()
                ->route('admin.studyclub.edit', $editionId)
                ->with('success', 'Artigo adicionado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar Study Club Item', ['edition_id' => $editionId, 'error' => $e->getMessage()]);

            return back()
                ->with('error', 'Erro ao adicionar artigo. Tente novamente.')
                ->withInput();
        }
    }

    /**
     * Atualiza um item (artigo)
     */
    public function updateItem(StoreItemRequest $request, int $itemId)
    {
        try {
            $item = $this->repository->findItemById($itemId);

            if (!$item) {
                abort(404, 'Artigo não encontrado');
            }

            $editionId = $item->edition_id;
            $data = $request->validated();
            
            // Processa upload de nova imagem
            if ($request->hasFile('image')) {
                // Deleta imagem antiga
                if ($item->image_path && Storage::disk('public')->exists($item->image_path)) {
                    Storage::disk('public')->delete($item->image_path);
                }
                $data['image_path'] = $this->uploadImage($request->file('image'));
            }

            $item->fill($data);
            $this->repository->saveItem($item);

            Log::info('Study Club Item atualizado', ['item_id' => $itemId, 'user' => auth()->id()]);

            return redirect()
                ->route('admin.studyclub.edit', $editionId)
                ->with('success', 'Artigo atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar Study Club Item', ['item_id' => $itemId, 'error' => $e->getMessage()]);

            return back()
                ->with('error', 'Erro ao atualizar artigo. Tente novamente.')
                ->withInput();
        }
    }

    /**
     * Remove item
     */
    public function destroyItem(int $itemId)
    {
        try {
            $item = $this->repository->findItemById($itemId);

            if (!$item) {
                abort(404, 'Item não encontrado');
            }

            $editionId = $item->edition_id;

            // Deleta imagem
            if ($item->image_path && Storage::disk('public')->exists($item->image_path)) {
                Storage::disk('public')->delete($item->image_path);
            }

            $this->repository->deleteItem($item);

            Log::info('Study Club Item deletado', ['item_id' => $itemId]);

            return redirect()
                ->route('admin.studyclub.edit', $editionId)
                ->with('success', 'Artigo removido com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao deletar Study Club Item', ['item_id' => $itemId, 'error' => $e->getMessage()]);

            return back()->with('error', 'Erro ao remover artigo. Tente novamente.');
        }
    }

    /**
     * Upload de imagem para storage
     */
    private function uploadImage($file): string
    {
        $filename = 'studyclub/' . uniqid() . '_' . $file->getClientOriginalName();
        $file->storeAs('', $filename, 'public');

        return $filename;
    }

    /**
     * Lista todos os administradores do Study Club
     */
    public function admins()
    {
        $admins = \App\Models\StudyClubAdmin::orderBy('created_at', 'desc')->get();
        return view('admin.studyclub.admins', compact('admins'));
    }

    /**
     * Adiciona um novo administrador
     */
    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:studyclub_admins,email',
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,editor',
        ]);

        \App\Models\StudyClubAdmin::create([
            'email' => $validated['email'],
            'name' => $validated['name'],
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.studyclub.admins')
            ->with('success', 'Administrador adicionado com sucesso!');
    }

    /**
     * Remove um administrador
     */
    public function destroyAdmin(int $id)
    {
        $admin = \App\Models\StudyClubAdmin::findOrFail($id);
        
        // Não permitir remover o próprio usuário
        $currentUserEmail = session()->get('usuario')->email ?? null;
        if ($admin->email === $currentUserEmail) {
            return redirect()->route('admin.studyclub.admins')
                ->with('error', 'Você não pode remover a si mesmo!');
        }

        $admin->delete();

        return redirect()->route('admin.studyclub.admins')
            ->with('success', 'Administrador removido com sucesso!');
    }
}
