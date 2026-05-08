@extends('facelift2.master')

@section('content')
<!-- Meta Tags de não-indexação (Desejado pelo usuário) -->
<meta name="robots" content="noindex, nofollow">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(isset($needsAuth) && $needsAuth)
                <!-- Formulário de Acesso por Senha -->
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 mt-5">
                            <div class="card-body p-5 text-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex p-3 mb-4">
                                    <i class="fas fa-lock fa-3x"></i>
                                </div>
                                <h4 class="fw-bold mb-3">Acesso Restrito</h4>
                                <p class="text-muted mb-4 small">Informe a senha para acessar o painel de Flush.</p>
                                <form action="{{ route('flush_cache') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="password" name="auth_password" class="form-control form-control-lg rounded-3 text-center" placeholder="Senha de Acesso" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 btn-lg rounded-3">Acessar Painel</button>
                                </form>
                                <div class="mt-4">
                                    <a href="{{ route('home') }}" class="text-secondary small text-decoration-none">Voltar para a Home</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Dashboard de Flush (Autorizado) -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 fw-bold text-dark"><i class="fas fa-broom me-2 text-primary"></i>Painel de Controle de Cache</h1>
                    <div class="d-flex gap-2">
                        <form action="{{ route('flush_cache') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="action" value="logout">
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-sign-out-alt me-1"></i>Sair</button>
                        </form>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-home me-1"></i>Home Site</a>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Seções Granulares -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
                            <div class="card-body p-4 text-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex p-3 mb-3">
                                    <i class="fas fa-home fa-2x"></i>
                                </div>
                                <h5 class="card-title fw-bold">Página Inicial</h5>
                                <p class="card-text text-muted small">Limpa os dados globais da Home (Novidades, Carrosséis, etc).</p>
                                <form action="{{ route('flush_cache') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="home">
                                    <button type="submit" class="btn btn-primary w-100 rounded-3">Limpar Home</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
                            <div class="card-body p-4 text-center">
                                <div class="bg-info bg-opacity-10 text-info rounded-circle d-inline-flex p-3 mb-3">
                                    <i class="fas fa-video fa-2x"></i>
                                </div>
                                <h5 class="card-title fw-bold">Vídeos & Canais</h5>
                                <p class="card-text text-muted small">Reseta todas as listas de vídeos, canais e seus backups.</p>
                                <form action="{{ route('flush_cache') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="videos">
                                    <button type="submit" class="btn btn-info text-white w-100 rounded-3">Limpar Vídeos</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
                            <div class="card-body p-4 text-center">
                                <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex p-3 mb-3">
                                    <i class="fas fa-book fa-2x"></i>
                                </div>
                                <h5 class="card-title fw-bold">GoBooks</h5>
                                <p class="card-text text-muted small">Limpa a lista de livros disponíveis e banners associados.</p>
                                <form action="{{ route('flush_cache') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="livros">
                                    <button type="submit" class="btn btn-warning text-white w-100 rounded-3">Limpar Livros</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
                            <div class="card-body p-4 text-center">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-3 mb-3">
                                    <i class="fas fa-layer-group fa-2x"></i>
                                </div>
                                <h5 class="card-title fw-bold">Estrutura de Coleções</h5>
                                <p class="card-text text-muted small">Limpa a lista de coleções (Revistas) e categorias globais.</p>
                                <form action="{{ route('flush_cache') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="colecoes">
                                    <button type="submit" class="btn btn-success w-100 rounded-3">Limpar Coleções</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
                            <div class="card-body p-4 text-center">
                                <div class="bg-dark bg-opacity-10 text-dark rounded-circle d-inline-flex p-3 mb-3">
                                    <i class="fas fa-key fa-2x"></i>
                                </div>
                                <h5 class="card-title fw-bold">Token de API</h5>
                                <p class="card-text text-muted small">Força um novo login na DentalGo e renova o Token global.</p>
                                <form action="{{ route('flush_cache') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="token">
                                    <button type="submit" class="btn btn-dark w-100 rounded-3">Renovar Token</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Campo de ID Individual -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-4 text-center">
                                <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-inline-flex p-3 mb-3">
                                    <i class="fas fa-search fa-2x"></i>
                                </div>
                                <h5 class="card-title fw-bold">Item Específico</h5>
                                <p class="card-text text-muted small">Limpa uma única coleção ou revista por seu ID numérico.</p>
                                <form action="{{ route('flush_cache') }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    <input type="number" name="id" class="form-control form-control-sm rounded-3" placeholder="ID" required>
                                    <select name="action" class="form-select form-select-sm rounded-3">
                                        <option value="individual_colecao">Coleção</option>
                                        <option value="individual_revista">Revista</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary btn-sm rounded-3"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Zona de Perigo -->
                <div class="mt-5 border-top pt-5">
                    <div class="card border-danger border-opacity-25 shadow-sm rounded-4 bg-danger bg-opacity-10">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="fw-bold text-danger"><i class="fas fa-fire me-2"></i>Zona de Perigo</h4>
                                    <p class="text-danger mb-0 opacity-75">Isso irá limpar **TODO o Cache** do Laravel, incluindo rotas e sessões globais.</p>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <form action="{{ route('flush_cache') }}" method="POST" onsubmit="return confirm('ATENÇÃO: Deseja realmente LIMPAR TUDO? Isso pode tornar o site lento por alguns minutos.')">
                                        @csrf
                                        <input type="hidden" name="action" value="all">
                                        <button type="submit" class="btn btn-danger px-4 py-2 fw-bold text-white shadow-sm rounded-3">FORÇAR LIMPEZA TOTAL</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .transition-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .card-title {
        color: #333;
    }
    .btn {
        transition: all 0.2s;
    }
</style>
@endsection
