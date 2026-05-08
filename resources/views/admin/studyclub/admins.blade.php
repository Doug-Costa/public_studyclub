@extends('facelift2.master')

@section('title', 'Gerenciar Admins - Study Club')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Administradores do Study Club</h1>
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Form para adicionar admin -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Adicionar Novo Administrador</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.studyclub.admins.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <input type="email" name="email" class="form-control" placeholder="Email do usuário" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="name" class="form-control" placeholder="Nome" required>
                            </div>
                            <div class="col-md-3">
                                <select name="role" class="form-select">
                                    <option value="editor">Editor</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success w-100">Adicionar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de admins -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Administradores Cadastrados</h5>
                </div>
                <div class="card-body">
                    @if($admins->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Função</th>
                                    <th>Status</th>
                                    <th>Último Login</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($admins as $admin)
                                    <tr>
                                        <td>{{ $admin->name }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $admin->role === 'admin' ? 'danger' : 'info' }}">
                                                {{ ucfirst($admin->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $admin->is_active ? 'success' : 'secondary' }}">
                                                {{ $admin->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td>{{ $admin->last_login_at ? $admin->last_login_at->format('d/m/Y H:i') : 'Nunca' }}</td>
                                        <td>
                                            <form action="{{ route('admin.studyclub.admins.destroy', $admin->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remover este admin?')">Remover</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Nenhum administrador cadastrado.</p>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.studyclub.index') }}" class="btn btn-primary">Voltar para Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection
