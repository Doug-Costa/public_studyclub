@extends('facelift2.master')

@section('title', 'Admin - Study Club')

@section('content')
<div class="container py-5">
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
            <h1 class="display-6 fw-bold">Study Club Admin</h1>
            <p class="text-muted mb-0">Gerencie as edições e artigos do Study Club</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.studyclub.checkout') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-cart-check me-2"></i>Testar Checkout
            </a>
            <a href="{{ route('admin.studyclub.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-lg me-2"></i>Nova Edição
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-collection me-2"></i>Edições</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            <th>Data</th>
                            <th>Artigos</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($editions as $edition)
                            <tr>
                                <td class="fw-bold">{{ $edition->number }}</td>
                                <td>
                                    {{ Str::limit($edition->title, 40) }}
                                    @if($edition->description)
                                        <br><small class="text-muted">{{ Str::limit($edition->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>{{ $edition->formatted_date }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        <i class="bi bi-file-text me-1"></i>{{ $edition->items->count() }}
                                    </span>
                                </td>
                                <td>
                                    @if($edition->status && $edition->isPublished())
                                        <span class="badge bg-success">Publicada</span>
                                    @elseif($edition->status)
                                        <span class="badge bg-warning text-dark">Agendada</span>
                                    @else
                                        <span class="badge bg-secondary">Inativa</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.studyclub.edit', $edition->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.studyclub.destroy', $edition->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta edição e todos os seus artigos?')">
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
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                    Nenhuma edição cadastrada.
                                    <br><a href="{{ route('admin.studyclub.create') }}">Criar primeira edição</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
