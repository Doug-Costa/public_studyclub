@extends('facelift2.master')

@section('title', 'Adicionar Artigo - Study Club Admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Header com info do admin --}}
            <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        <i class="bi bi-person-fill fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Logado como</small>
                        <strong class="text-primary">{{ session('studyclub_admin_name', 'Admin') }}</strong>
                    </div>
                </div>
                <a href="{{ route('studyclub.admin.logout') }}" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i>Sair
                </a>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="display-6 fw-bold">Adicionar Artigo</h1>
                    <p class="text-muted mb-0">Edição #{{ $edition->number }}: {{ $edition->title }}</p>
                </div>
                <a href="{{ route('admin.studyclub.edit', $edition->id) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Voltar
                </a>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.studyclub.items.store', $edition->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Novo Artigo</h5>
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

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label fw-bold">Categoria *</label>
                                <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}" placeholder="Ex: ORTODONTIA" required>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label fw-bold">Tipo *</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Selecione...</option>
                                    <option value="article" {{ old('type') == 'article' ? 'selected' : '' }}>Artigo</option>
                                    <option value="interview" {{ old('type') == 'interview' ? 'selected' : '' }}>Entrevista</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="type_label" class="form-label fw-bold">Rótulo do Tipo *</label>
                            <input type="text" class="form-control @error('type_label') is-invalid @enderror" id="type_label" name="type_label" value="{{ old('type_label') }}" placeholder="Ex: Artigo Original, Entrevista..." required>
                            @error('type_label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label fw-bold">Autor(es) *</label>
                            <input type="text" class="form-control @error('author') is-invalid @enderror" id="author" name="author" value="{{ old('author') }}" required>
                            @error('author')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Título *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="summary" class="form-label fw-bold">Resumo *</label>
                            <textarea class="form-control @error('summary') is-invalid @enderror" id="summary" name="summary" rows="3" required>{{ old('summary') }}</textarea>
                            @error('summary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="findings" class="form-label fw-bold">Achados *</label>
                            <textarea class="form-control @error('findings') is-invalid @enderror" id="findings" name="findings" rows="3" required>{{ old('findings') }}</textarea>
                            @error('findings')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="implications" class="form-label fw-bold">Implicações *</label>
                            <textarea class="form-control @error('implications') is-invalid @enderror" id="implications" name="implications" rows="3" required>{{ old('implications') }}</textarea>
                            @error('implications')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="external_url" class="form-label fw-bold">URL Externa (DentalGO) *</label>
                            <input type="url" class="form-control @error('external_url') is-invalid @enderror" id="external_url" name="external_url" value="{{ old('external_url') }}" placeholder="https://dentalgo.com.br/..." required>
                            @error('external_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="icon" class="form-label fw-bold">Ícone (Bootstrap Icons)</label>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', 'bi-journal-text') }}" placeholder="bi-journal-text">
                                <small class="text-muted">Ex: bi-journal-text, bi-chat-dots, bi-camera-video</small>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label fw-bold">Imagem</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="likes" class="form-label fw-bold">Likes (opcional)</label>
                                <input type="number" class="form-control @error('likes') is-invalid @enderror" id="likes" name="likes" value="{{ old('likes', 0) }}" min="0">
                                @error('likes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="comments" class="form-label fw-bold">Comentários (opcional)</label>
                                <input type="number" class="form-control @error('comments') is-invalid @enderror" id="comments" name="comments" value="{{ old('comments', 0) }}" min="0">
                                @error('comments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.studyclub.edit', $edition->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Salvar Artigo
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
