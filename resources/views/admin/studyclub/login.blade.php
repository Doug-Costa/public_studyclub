@extends('facelift2.master')

@section('title', 'Login - Study Club Admin')

@section('content')
<style>
    .login-container {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-card {
        max-width: 400px;
        width: 100%;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .login-header {
        background: linear-gradient(135deg, #d21d5b 0%, #0056b3 100%);
        color: white;
        padding: 30px;
        border-radius: 12px 12px 0 0;
        text-align: center;
    }
    .login-header h2 {
        margin: 0;
        font-size: 1.5rem;
    }
    .login-header p {
        margin: 10px 0 0 0;
        opacity: 0.9;
        font-size: 0.9rem;
    }
    .login-body {
        padding: 30px;
        background: white;
        border-radius: 0 0 12px 12px;
    }
    .form-control {
        border-radius: 8px;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
    }
    .form-control:focus {
        border-color: #d21d5b;
        box-shadow: 0 0 0 0.2rem rgba(210, 29, 91, 0.25);
    }
    .btn-login {
        background: linear-gradient(135deg, #d21d5b 0%, #0056b3 100%);
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-weight: 600;
        width: 100%;
        color: white;
    }
    .btn-login:hover {
        opacity: 0.9;
        color: white;
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h2><i class="bi bi-journal-bookmark me-2"></i>Study Club</h2>
            <p>Acesso restrito - Administradores</p>
        </div>
        
        <div class="login-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('studyclub.admin.login.post') }}">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Usuário</label>
                    <input type="text" 
                           name="username" 
                           class="form-control" 
                           placeholder="Digite seu usuário"
                           value="{{ old('username') }}"
                           required
                           autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Senha</label>
                    <input type="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Digite sua senha"
                           required>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('facehome') }}" class="text-muted text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i>Voltar para o site
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
