@extends('layouts.app')
@section('title', 'Conectando Talentos Freelancers')

@section('styles')
<style>
    .hero-landing {
        background: linear-gradient(140deg, #003d73 0%, #0d5fa6 45%, #1e84d4 100%);
        padding: 96px 0 80px; position: relative; overflow: hidden;
    }
    .hero-landing::before {
        content: ''; position: absolute; width: 700px; height: 700px;
        background: radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 70%);
        border-radius: 50%; top: -200px; right: -80px; pointer-events: none;
    }
    .hero-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25); color: white; border-radius: 50px; padding: 6px 18px; font-size: .85rem; font-weight: 500; margin-bottom: 24px; }
    .hero-title { font-size: clamp(2rem,5vw,3.4rem); font-weight: 800; color: white; line-height: 1.18; margin-bottom: 20px; letter-spacing: -.5px; }
    .hero-subtitle { font-size: 1.1rem; color: rgba(255,255,255,.8); max-width: 500px; margin: 0 auto 40px; line-height: 1.7; }
    .hero-search-wrap { max-width: 560px; margin: 0 auto 28px; }
    .hero-search-wrap .form-control { height: 54px; border-radius: 12px 0 0 12px; border: none; padding: 0 20px; font-size: .95rem; box-shadow: none; }
    .btn-search { height: 54px; border-radius: 0 12px 12px 0; background: #004e8d; border: none; color: white; padding: 0 28px; font-weight: 600; font-size: .95rem; transition: background .2s; display: flex; align-items: center; gap: 8px; }
    .btn-search:hover { background: #003a6e; }
    .popular-tag { display: inline-block; background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.2); color: rgba(255,255,255,.9); border-radius: 50px; padding: 4px 14px; font-size: .82rem; text-decoration: none; transition: background .2s; }
    .popular-tag:hover { background: rgba(255,255,255,.28); color: white; }
    .stats-section { background: white; padding: 36px 0; border-bottom: 1px solid #e4eef8; }
    .stat-divider { border-right: 1px solid #e4eef8; }
    .stat-number { font-size: 2rem; font-weight: 800; color: #1976d2; line-height: 1; }
    .stat-label { color: #6c757d; font-size: .85rem; margin-top: 5px; }
    .how-section { background: #ecf5ff; padding: 88px 0; }
    .section-label { font-size: .8rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: #1976d2; margin-bottom: 12px; }
    .section-title { font-size: clamp(1.6rem,3vw,2.2rem); font-weight: 800; color: #004e8d; margin-bottom: 12px; letter-spacing: -.3px; }
    .section-subtitle { color: #6c757d; font-size: 1rem; max-width: 480px; margin: 0 auto; }
    .step-card { background: white; border-radius: 18px; padding: 36px 28px; border: 1px solid #d0e6f8; text-align: center; transition: transform .25s, box-shadow .25s; height: 100%; }
    .step-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(25,118,210,.13); }
    .step-icon { width: 68px; height: 68px; background: linear-gradient(135deg,#1976d2,#42a5f5); border-radius: 18px; display: flex; align-items: center; justify-content: center; margin: 0 auto 22px; box-shadow: 0 8px 20px rgba(25,118,210,.25); }
    .step-icon i { color: white; font-size: 1.5rem; }
    .step-number-label { font-size: .75rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #1976d2; margin-bottom: 8px; }
    .step-card h5 { font-weight: 700; color: #1a2e44; margin-bottom: 10px; font-size: 1.05rem; }
    .categories-section { background: white; padding: 88px 0; }
    .category-card { border: 1.5px solid #d0e6f8; border-radius: 14px; padding: 22px 12px; text-align: center; text-decoration: none; display: flex; flex-direction: column; align-items: center; transition: all .22s; color: #333; background: #f8fbff; }
    .category-card:hover { background: #1976d2; color: white; border-color: #1976d2; transform: translateY(-3px); box-shadow: 0 8px 24px rgba(25,118,210,.2); }
    .category-card:hover .cat-icon { color: white; }
    .cat-icon { font-size: 1.8rem; color: #1976d2; margin-bottom: 10px; transition: color .22s; }
    .cat-label { font-size: .88rem; font-weight: 600; }
    .cta-section { background: linear-gradient(140deg,#003d73 0%,#1976d2 100%); padding: 88px 0; text-align: center; position: relative; overflow: hidden; }
    .cta-section::before { content: ''; position: absolute; width: 400px; height: 400px; border-radius: 50%; background: rgba(255,255,255,.05); top: -120px; left: -80px; }
    .btn-cta-white { background: white; color: #1976d2; border: none; padding: 14px 36px; border-radius: 50px; font-weight: 700; font-size: .97rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all .2s; }
    .btn-cta-white:hover { background: #e8f3ff; color: #004e8d; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.15); }
    .btn-cta-outline { background: transparent; color: white; border: 2px solid rgba(255,255,255,.6); padding: 14px 36px; border-radius: 50px; font-weight: 600; font-size: .97rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all .2s; }
    .btn-cta-outline:hover { background: rgba(255,255,255,.12); border-color: white; color: white; }
</style>
@endsection

@section('content')
{{-- HERO --}}
<section class="hero-landing">
    <div class="container position-relative text-center" style="z-index:1;">
        <div class="hero-badge">
            <i class="fa-solid fa-location-dot" style="font-size:.8rem;"></i>
            Plataforma local &mdash; Jaraguá do Sul e região
        </div>
        <h1 class="hero-title">Encontre o talento certo<br>para o seu projeto</h1>
        <p class="hero-subtitle">Conectamos freelancers talentosos com empresas e contratantes da região.<br>Rápido, simples e gratuito.</p>
        <div class="hero-search-wrap">
            <form action="{{ route('vagas.index') }}" method="GET" class="d-flex">
                <input type="text" name="q" class="form-control flex-grow-1" placeholder="Ex: Designer, Desenvolvedor, Redator...">
                <button type="submit" class="btn-search"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
            </form>
        </div>
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-2">
            <span style="color:rgba(255,255,255,.6);font-size:.82rem;font-weight:500;">Popular:</span>
            <a href="{{ route('vagas.index', ['q' => 'design']) }}" class="popular-tag">Design</a>
            <a href="{{ route('vagas.index', ['q' => 'desenvolvimento']) }}" class="popular-tag">Desenvolvimento</a>
            <a href="{{ route('vagas.index', ['q' => 'marketing']) }}" class="popular-tag">Marketing</a>
            <a href="{{ route('vagas.index', ['q' => 'redação']) }}" class="popular-tag">Redação</a>
            <a href="{{ route('vagas.index', ['q' => 'fotografia']) }}" class="popular-tag">Fotografia</a>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="stats-section">
    <div class="container">
        <div class="row justify-content-center text-center g-0">
            <div class="col-6 col-md-3 stat-divider py-2"><div class="stat-number">500+</div><div class="stat-label">Freelancers Cadastrados</div></div>
            <div class="col-6 col-md-3 stat-divider py-2"><div class="stat-number">1.2k+</div><div class="stat-label">Vagas Publicadas</div></div>
            <div class="col-6 col-md-3 stat-divider py-2"><div class="stat-number">300+</div><div class="stat-label">Empresas Parceiras</div></div>
            <div class="col-6 col-md-3 py-2"><div class="stat-number">98%</div><div class="stat-label">Satisfação dos Usuários</div></div>
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="how-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label">Plataforma</div>
            <h2 class="section-title">Como funciona?</h2>
            <p class="section-subtitle">Três passos simples para começar sua jornada no MeuFreela</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4"><div class="step-card">
                <div class="step-icon"><i class="fa-solid fa-user-plus"></i></div>
                <div class="step-number-label">Passo 01</div>
                <h5>Crie sua Conta</h5>
                <p class="text-muted mb-0" style="font-size:.93rem;line-height:1.7;">Cadastre-se gratuitamente como freelancer ou contratante em menos de 2 minutos.</p>
            </div></div>
            <div class="col-md-4"><div class="step-card">
                <div class="step-icon"><i class="fa-solid fa-briefcase"></i></div>
                <div class="step-number-label">Passo 02</div>
                <h5>Explore Oportunidades</h5>
                <p class="text-muted mb-0" style="font-size:.93rem;line-height:1.7;">Navegue pelas vagas disponíveis e encontre a que mais combina com suas habilidades.</p>
            </div></div>
            <div class="col-md-4"><div class="step-card">
                <div class="step-icon"><i class="fa-solid fa-handshake"></i></div>
                <div class="step-number-label">Passo 03</div>
                <h5>Candidate-se</h5>
                <p class="text-muted mb-0" style="font-size:.93rem;line-height:1.7;">Envie sua candidatura com um clique e aguarde o retorno do contratante.</p>
            </div></div>
        </div>
    </div>
</section>

{{-- CATEGORIES --}}
<section class="categories-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label">Áreas</div>
            <h2 class="section-title">Categorias Populares</h2>
            <p class="section-subtitle">Encontre vagas na sua área de atuação</p>
        </div>
        <div class="row g-3 justify-content-center">
            <div class="col-6 col-sm-4 col-md-2"><a href="{{ route('vagas.index', ['q' => 'design']) }}" class="category-card"><span class="cat-icon"><i class="fa-solid fa-palette"></i></span><span class="cat-label">Design</span></a></div>
            <div class="col-6 col-sm-4 col-md-2"><a href="{{ route('vagas.index', ['q' => 'desenvolvimento']) }}" class="category-card"><span class="cat-icon"><i class="fa-solid fa-code"></i></span><span class="cat-label">Dev</span></a></div>
            <div class="col-6 col-sm-4 col-md-2"><a href="{{ route('vagas.index', ['q' => 'marketing']) }}" class="category-card"><span class="cat-icon"><i class="fa-solid fa-bullhorn"></i></span><span class="cat-label">Marketing</span></a></div>
            <div class="col-6 col-sm-4 col-md-2"><a href="{{ route('vagas.index', ['q' => 'redação']) }}" class="category-card"><span class="cat-icon"><i class="fa-solid fa-pen-nib"></i></span><span class="cat-label">Redação</span></a></div>
            <div class="col-6 col-sm-4 col-md-2"><a href="{{ route('vagas.index', ['q' => 'fotografia']) }}" class="category-card"><span class="cat-icon"><i class="fa-solid fa-camera"></i></span><span class="cat-label">Fotografia</span></a></div>
            <div class="col-6 col-sm-4 col-md-2"><a href="{{ route('vagas.index') }}" class="category-card"><span class="cat-icon"><i class="fa-solid fa-th"></i></span><span class="cat-label">Ver Todas</span></a></div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="cta-section">
    <div class="container position-relative" style="z-index:1;">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-label" style="color:rgba(255,255,255,.6);">Comece Agora</div>
                <h2 style="font-size:clamp(1.8rem,4vw,2.4rem);font-weight:800;color:white;margin-bottom:16px;letter-spacing:-.3px;">Pronto para dar o próximo passo?</h2>
                <p style="color:rgba(255,255,255,.75);font-size:1rem;margin-bottom:40px;line-height:1.7;">Junte-se a centenas de freelancers e contratantes que já encontraram oportunidades no MeuFreela.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn-cta-white"><i class="fa-solid fa-rocket"></i> Criar conta grátis</a>
                    <a href="{{ route('vagas.index') }}" class="btn-cta-outline"><i class="fa-solid fa-magnifying-glass"></i> Ver vagas disponíveis</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
