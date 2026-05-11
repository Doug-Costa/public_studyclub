@extends('facelift2.master')

@section('title', 'Editar Edição - Study Club Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        {{-- Formulário de Edição --}}
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Edição</h5>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.studyclub.update', $edition->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="number" class="form-label">Número *</label>
                            <input type="number" 
                                   class="form-control @error('number') is-invalid @enderror" 
                                   id="number" 
                                   name="number" 
                                   value="{{ old('number', $edition->number) }}" 
                                   required 
                                   min="1">
                            @error('number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Título *</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $edition->title) }}" 
                                   required 
                                   maxlength="255">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ old('description', $edition->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="publish_date" class="form-label">Data de Publicação *</label>
                            <input type="date" 
                                   class="form-control @error('publish_date') is-invalid @enderror" 
                                   id="publish_date" 
                                   name="publish_date" 
                                   value="{{ old('publish_date', $edition->publish_date->format('Y-m-d')) }}" 
                                   required>
                            @error('publish_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="status" 
                                       name="status" 
                                       value="1" 
                                       {{ old('status', $edition->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    Edição ativa
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Atualizar Edição
                            </button>
                            <a href="{{ route('admin.studyclub.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Lista de Artigos --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Artigos ({{ $edition->items->count() }})</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="bi bi-plus-lg me-1"></i>Adicionar Artigo
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Título</th>
                                    <th>Categoria</th>
                                    <th>Likes</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($edition->items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ Str::limit($item->title, 50) }}</strong>
                                            <br><small class="text-muted">{{ $item->author }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $item->category }}</span>
                                            <br><small class="text-muted">{{ $item->type_label }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-heart-fill text-danger me-1"></i>{{ $item->likes }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-outline-primary me-1 btn-edit-item" 
                                                    data-item="{{ json_encode($item) }}"
                                                    data-action="{{ route('admin.studyclub.items.update', $item->id) }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('admin.studyclub.items.destroy', $item->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este artigo?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                            Nenhum artigo nesta edição.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Estilos para Modais no facelift2 --}}
<style>
    .modal-fixed {
        position: fixed !important;
        z-index: 2147483647 !important;
        transform: translateZ(0) !important;
        will-change: transform, opacity !important;
        padding-top: 80px !important;
    }
    .modal-fixed .modal-dialog {
        z-index: 2147483647 !important;
        position: relative !important;
        margin-top: 20px !important;
    }
    .modal-fixed .modal-content {
        z-index: 2147483647 !important;
        box-shadow: 0 10px 40px rgba(0,0,0,0.8) !important;
        border: 3px solid #fff !important;
        background: white !important;
    }
    #customBackdrop {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background-color: rgba(0,0,0,0.6) !important;
        z-index: 2147483646 !important;
        display: none;
    }
    body.modal-open #customBackdrop {
        display: block !important;
    }
    body .modal-backdrop {
        display: none !important;
    }
</style>

{{-- Backdrop Customizado --}}
<div id="customBackdrop"></div>

{{-- Modal: Adicionar Artigo --}}
<div class="modal fade modal-fixed" id="addItemModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Adicionar Artigo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.studyclub.items.store', $edition->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @include('admin.studyclub.items._form_fields')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Adicionar Artigo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Editar Artigo --}}
<div class="modal fade modal-fixed" id="editItemModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar Artigo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editItemForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    @include('admin.studyclub.items._form_fields', ['isEdit' => true])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info text-white">
                        <i class="bi bi-check-lg me-2"></i>Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const customBackdrop = document.getElementById('customBackdrop');
    
    // Função para configurar z-index e backdrops dos modais
    function setupModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        
        modal.addEventListener('shown.bs.modal', function() {
            customBackdrop.style.display = 'block';
            modal.style.zIndex = '2147483647';
            document.querySelectorAll('.modal-backdrop').forEach(b => b.style.display = 'none');
        });
        
        modal.addEventListener('hidden.bs.modal', function() {
            customBackdrop.style.display = 'none';
        });
    }

    setupModal('addItemModal');
    setupModal('editItemModal');

    // Lógica para abrir modal de edição e preencher campos
    document.querySelectorAll('.btn-edit-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const item = JSON.parse(this.getAttribute('data-item'));
            const action = this.getAttribute('data-action');
            const form = document.getElementById('editItemForm');
            
            form.action = action;
            
            // Preencher campos
            form.querySelector('[name="category"]').value = item.category;
            form.querySelector('[name="type"]').value = item.type;
            form.querySelector('[name="type_label"]').value = item.type_label;
            form.querySelector('[name="author"]').value = item.author;
            form.querySelector('[name="title"]').value = item.title;
            form.querySelector('[name="resumo"]').value = item.resumo;
            form.querySelector('[name="achados"]').value = item.achados;
            form.querySelector('[name="implicacoes"]').value = item.implicacoes;
            form.querySelector('[name="external_url"]').value = item.external_url;
            form.querySelector('[name="icon"]').value = item.icon;
            form.querySelector('[name="likes"]').value = item.likes || 0;
            
            // Abrir modal
            const editModal = new bootstrap.Modal(document.getElementById('editItemModal'));
            editModal.show();
        });
    });
});
</script>
@endsection
