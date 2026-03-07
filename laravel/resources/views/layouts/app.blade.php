<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MeuFreela') - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #ecf5ff; }
        .landing-nav {
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #e4eef8;
            padding: .85rem 0;
            position: sticky; top: 0; z-index: 1030;
        }
        .nav-logo-icon {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, #1976d2, #004e8d);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
        }
        .nav-link-item { color: #444; text-decoration: none; font-weight: 500; font-size: .95rem; transition: color .2s; }
        .nav-link-item:hover { color: #1976d2; }
        .landing-footer { background: #0e1e30; color: rgba(255,255,255,.65); padding: 52px 0 24px; }
        .footer-logo-text { font-size: 1.2rem; font-weight: 800; color: white; }
        .footer-heading { color: white; font-weight: 700; font-size: .9rem; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 1px; }
        .footer-link { color: rgba(255,255,255,.5); text-decoration: none; font-size: .9rem; transition: color .2s; display: block; margin-bottom: 8px; }
        .footer-link:hover { color: white; }
        .footer-divider { border-color: rgba(255,255,255,.08); }
        .card { border: 1.5px solid #d0e6f8; border-radius: 14px; box-shadow: 0 4px 12px rgba(160,195,230,.35); }
        .btn-primary { background: #1976d2; border-color: #1976d2; }
        .btn-primary:hover { background: #004e8d; border-color: #004e8d; }
        .alert { border-radius: 10px; }
    </style>
    @yield('styles')
</head>
<body>

<nav class="landing-nav">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ route('home') }}" class="text-decoration-none d-flex align-items-center gap-2">
            <div class="nav-logo-icon">
                <i class="fa-solid fa-bolt" style="color:white;font-size:.95rem;"></i>
            </div>
            <span style="font-size:1.35rem;font-weight:800;color:#004e8d;">MeuFreela</span>
        </a>
        <div class="d-flex align-items-center gap-4">
            <a href="{{ route('vagas.index') }}" class="nav-link-item d-none d-md-inline">Ver Vagas</a>
            @auth
                <a href="{{ route('dashboard') }}" class="nav-link-item" style="color:#1976d2;font-weight:600;">Meu Painel</a>
                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf
                    <button class="btn btn-outline-danger rounded-pill px-3 py-1" style="font-size:.9rem;">Sair</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link-item" style="color:#1976d2;font-weight:600;">Entrar</a>
                <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 py-2" style="font-size:.9rem;font-weight:600;">
                    Cadastrar-se
                </a>
            @endauth
        </div>
    </div>
</nav>

<main>
    @if(session('success'))
        <div class="container pt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="container pt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<footer class="landing-footer">
    <div class="container">
        <div class="row pb-4 g-4">
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="nav-logo-icon"><i class="fa-solid fa-bolt" style="color:white;font-size:.85rem;"></i></div>
                    <span class="footer-logo-text">MeuFreela</span>
                </div>
                <p style="font-size:.88rem;color:rgba(255,255,255,.45);line-height:1.7;">
                    Conectando talentos freelancers com oportunidades em Jaraguá do Sul e região.
                </p>
            </div>
            <div class="col-6 col-md-2 offset-md-2">
                <div class="footer-heading">Plataforma</div>
                <a href="{{ route('home') }}" class="footer-link">Início</a>
                <a href="{{ route('vagas.index') }}" class="footer-link">Ver Vagas</a>
                <a href="{{ route('register') }}" class="footer-link">Cadastrar-se</a>
                <a href="{{ route('login') }}" class="footer-link">Entrar</a>
            </div>
            <div class="col-6 col-md-4">
                <div class="footer-heading">Para Contratantes</div>
                <a href="{{ route('register') }}" class="footer-link">Publicar Vaga</a>
                <a href="{{ route('vagas.index') }}" class="footer-link">Encontrar Freelancers</a>
                <a href="{{ route('login') }}" class="footer-link">Acessar Painel</a>
            </div>
        </div>
        <hr class="footer-divider">
        <div class="text-center pt-3">
            <p style="font-size:.82rem;color:rgba(255,255,255,.3);margin:0;">&copy; {{ date('Y') }} MeuFreela &mdash; Todos os direitos reservados.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
