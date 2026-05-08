@extends('facelift2.master')

@section('title', 'Nova Edição - Study Club Admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Nova Edição</h5>
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

                    <form action="{{ route('admin.studyclub.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="number" class="form-label">Número da Edição *</label>
                            <input type="number" 
                                   class="form-control @error('number') is-invalid @enderror" 
                                   id="number" 
                                   name="number" 
                                   value="{{ old('number', $nextNumber) }}" 
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
                                   value="{{ old('title', 'Study Club #' . $nextNumber) }}" 
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
                                      rows="3">{{ old('description') }}</textarea>
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
                                   value="{{ old('publish_date', date('Y-m-d')) }}" 
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
                                       {{ old('status', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    Edição ativa
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.studyclub.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Criar Edição
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
