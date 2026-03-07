<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Painel') - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f0f6ff; }
        .dash-sidebar {
            width: 240px; min-height: 100vh;
            background: white;
            border-right: 1px solid #dce8f5;
            box-shadow: 2px 0 12px rgba(0,78,141,.06);
            position: fixed; top: 0; left: 0; z-index: 1000;
            padding: 0;
        }
        .sidebar-logo {
            display: flex; align-items: center; gap: 10px;
            padding: 20px 20px 18px;
            border-bottom: 1px solid #edf4fc;
            text-decoration: none;
        }
        .sidebar-logo-icon {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #1976d2, #004e8d);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }
        .sidebar-logo-text { font-size: 1.15rem; font-weight: 800; color: #004e8d; }
        .sidebar-user {
            padding: 14px 20px;
            border-bottom: 1px solid #edf4fc;
        }
        .sidebar-user-name { font-weight: 700; font-size: .9rem; color: #1a2e44; line-height: 1.3; }
        .sidebar-user-type { font-size: .75rem; color: #1976d2; font-weight: 600; text-transform: capitalize; }
        .sidebar-nav { padding: 10px 12px; }
        .sidebar-section { font-size: .7rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #aab8c8; padding: 10px 8px 4px; }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 9px;
            text-decoration: none; color: #4a5568;
            font-size: .88rem; font-weight: 500;
            transition: all .18s; margin-bottom: 2px;
        }
        .sidebar-link:hover { background: #eef6ff; color: #1976d2; }
        .sidebar-link.active { background: #e8f3ff; color: #1976d2; font-weight: 700; }
        .sidebar-link i { width: 18px; text-align: center; opacity: .8; }
        .sidebar-logout { margin: 0 12px 16px; }
        .dash-content { margin-left: 240px; padding: 28px 32px; min-height: 100vh; }
        .page-header { margin-bottom: 24px; }
        .page-title { font-size: 1.5rem; font-weight: 800; color: #1a2e44; margin: 0; }
        .card { border: 1.5px solid #d0e6f8; border-radius: 14px; box-shadow: 0 2px 12px rgba(160,195,230,.25); }
        .btn-primary { background: #1976d2; border-color: #1976d2; }
        .btn-primary:hover { background: #004e8d; border-color: #004e8d; }
        .alert { border-radius: 10px; }
        @media (max-width: 767px) {
            .dash-sidebar { transform: translateX(-100%); }
            .dash-content { margin-left: 0; padding: 16px; }
        }
    </style>
    @yield('styles')
</head>
<body>

<aside class="dash-sidebar">
    <a href="{{ route('home') }}" class="sidebar-logo">
        <div class="sidebar-logo-icon"><i class="fa-solid fa-bolt" style="color:white;font-size:.85rem;"></i></div>
        <span class="sidebar-logo-text">MeuFreela</span>
    </a>

    <div class="sidebar-user">
        <div class="sidebar-user-name">{{ auth()->user()->nome }}</div>
        <div class="sidebar-user-type">{{ auth()->user()->tipo_usuario }}</div>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section">Geral</div>
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-user"></i> Meu Perfil
        </a>

        @if(in_array(auth()->user()->tipo_usuario, ['freelancer', 'ambos']))
            <div class="sidebar-section">Freelancer</div>
            <a href="{{ route('vagas.index') }}" class="sidebar-link {{ request()->routeIs('vagas.index') ? 'active' : '' }}">
                <i class="fa-solid fa-magnifying-glass"></i> Procurar Vagas
            </a>
            <a href="{{ route('minhas-candidaturas') }}" class="sidebar-link {{ request()->routeIs('minhas-candidaturas') ? 'active' : '' }}">
                <i class="fa-solid fa-paper-plane"></i> Minhas Candidaturas
            </a>
        @endif

        @if(in_array(auth()->user()->tipo_usuario, ['contratante', 'ambos']))
            <div class="sidebar-section">Contratante</div>
            <a href="{{ route('minhas-vagas') }}" class="sidebar-link {{ request()->routeIs('minhas-vagas') ? 'active' : '' }}">
                <i class="fa-solid fa-briefcase"></i> Minhas Vagas
            </a>
            <a href="{{ route('vagas.create') }}" class="sidebar-link {{ request()->routeIs('vagas.create') ? 'active' : '' }}">
                <i class="fa-solid fa-plus"></i> Publicar Vaga
            </a>
        @endif
    </nav>

    <div class="sidebar-logout mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill" style="font-size:.85rem;">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Sair
            </button>
        </form>
    </div>
</aside>

<div class="dash-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
