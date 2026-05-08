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

{{-- Fix para Modal no facelift2 --}}
<style>
    /* Forçar modal a quebrar stacking context */
    #addItemModal {
        position: fixed !important;
        z-index: 2147483647 !important;
        transform: translateZ(0) !important;
        will-change: transform, opacity !important;
        padding-top: 80px !important; /* Espaço para o header do Facelift2 */
    }
    
    /* Container do modal deve ter z-index alto */
    #addItemModal .modal-dialog {
        z-index: 2147483647 !important;
        position: relative !important;
        margin-top: 20px !important;
    }
    
    /* Conteúdo do modal */
    #addItemModal .modal-content {
        z-index: 2147483647 !important;
        box-shadow: 0 10px 40px rgba(0,0,0,0.8) !important;
        border: 3px solid #fff !important;
        background: white !important;
        position: relative !important;
    }
    
    /* Header com z-index mais alto - ACIMA DE TUDO */
    #addItemModal .modal-header {
        z-index: 2147483648 !important;
        position: relative !important;
        background: linear-gradient(135deg, #0056b3 0%, #d21d5b 100%) !important;
        transform: translateZ(0) !important; /* Quebra stacking context */
    }
    
    /* Forçar elementos do modal ficarem acima do sidebar-backdrop */
    #addItemModal .modal-header,
    #addItemModal .modal-body,
    #addItemModal .modal-footer,
    #addItemModal .modal-content {
        position: relative !important;
        z-index: 2147483647 !important;
    }
    
    /* Botão de fechar - garantir que está clicável */
    #addItemModal .btn-close {
        z-index: 2147483649 !important;
        position: relative !important;
        opacity: 1 !important;
        filter: none !important;
    }
    
    /* Desativar elementos do facelift2 quando modal aberto */
    body.modal-open .sidebar,
    body.modal-open .topbar,
    body.modal-open .sidebar-left,
    body.modal-open aside,
    body.modal-open header {
        pointer-events: none !important;
    }
    
    /* Backdrop customizado */
    #customBackdrop {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background-color: rgba(0,0,0,0.6) !important;
        z-index: 2147483646 !important;
        display: none;
        pointer-events: none !important;
    }
    
    body.modal-open #customBackdrop {
        display: block !important;
    }
    
    /* Esconder backdrop do Bootstrap */
    body .modal-backdrop,
    .modal-backdrop.show {
        display: none !important;
        z-index: -1 !important;
    }
</style>

{{-- Backdrop Customizado --}}
<div id="customBackdrop"></div>

{{-- Modal: Adicionar Artigo --}}
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Adicionar Artigo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.studyclub.items.store', $edition->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categoria *</label>
                            <input type="text" class="form-control" name="category" required 
                                   placeholder="Ex: ORTODONTIA">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo *</label>
                            <select class="form-select" name="type" required>
                                <option value="article">Artigo</option>
                                <option value="interview">Entrevista</option>
                                <option value="special">Especial</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rótulo do Tipo *</label>
                        <input type="text" class="form-control" name="type_label" required 
                               placeholder="Ex: Artigo Original, Entrevista...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Autor(es) *</label>
                        <input type="text" class="form-control" name="author" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Título *</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Resumo *</label>
                        <textarea class="form-control" name="resumo" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Achados *</label>
                        <textarea class="form-control" name="achados" rows="2" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Implicações *</label>
                        <textarea class="form-control" name="implicacoes" rows="2" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">URL Externa (DentalGO) *</label>
                        <input type="url" class="form-control" name="external_url" required
                               placeholder="https://dentalgo.com.br/...">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ícone (Bootstrap Icons)</label>
                            <input type="text" class="form-control" name="icon" value="bi-journal-text">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Imagem</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                    </div>
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

{{-- JavaScript para garantir modal funcione --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('addItemModal');
    var customBackdrop = document.getElementById('customBackdrop');
    
    if (modal) {
        // Forçar z-index quando o modal abrir
        modal.addEventListener('shown.bs.modal', function() {
            // Mostrar custom backdrop
            if (customBackdrop) {
                customBackdrop.style.display = 'block';
            }
            
            // Garantir que o modal está acima de tudo
            modal.style.zIndex = '2147483647';
            modal.style.position = 'fixed';
            
            // Esconder backdrop do Bootstrap se existir
            var bootstrapBackdrops = document.querySelectorAll('.modal-backdrop');
            bootstrapBackdrops.forEach(function(backdrop) {
                backdrop.style.display = 'none';
            });
            
            // Ajustar elementos internos do modal
            var dialog = modal.querySelector('.modal-dialog');
            if (dialog) {
                dialog.style.zIndex = '2147483647';
                dialog.style.position = 'relative';
            }
            var content = modal.querySelector('.modal-content');
            if (content) {
                content.style.zIndex = '2147483647';
                content.style.position = 'relative';
        });
        
        // Limpar quando fechar
        modal.addEventListener('hidden.bs.modal', function() {
            modal.style.zIndex = '';
            if (customBackdrop) {
                customBackdrop.style.display = 'none';
            }
        });
    }
});
</script>

@endsection
