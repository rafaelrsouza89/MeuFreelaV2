@extends('layouts.app')
@section('title', $vaga->titulo)

@section('content')
<div style="background:#ecf5ff;border-bottom:1px solid #d0e6f8;padding:32px 0 24px;">
    <div class="container">
        <a href="{{ route('vagas.index') }}" style="font-size:.88rem;color:#1976d2;text-decoration:none;"><i class="fa-solid fa-arrow-left me-1"></i>Voltar às vagas</a>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        {{-- Main Content --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius:18px;">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge" style="background:#ecf5ff;color:#1976d2;padding:6px 14px;border-radius:50px;font-size:.82rem;font-weight:600;">{{ ucfirst($vaga->tipo_vaga) }}</span>
                        @if($vaga->data_limite && $vaga->data_limite >= now()->toDateString())
                            <span class="badge bg-success" style="padding:6px 14px;border-radius:50px;font-size:.82rem;">Vaga Aberta</span>
                        @else
                            <span class="badge bg-danger" style="padding:6px 14px;border-radius:50px;font-size:.82rem;">Encerrada</span>
                        @endif
                    </div>
                    <h1 style="font-weight:800;color:#1a2e44;font-size:1.8rem;letter-spacing:-.3px;">{{ $vaga->titulo }}</h1>

                    <div class="d-flex flex-wrap gap-4 mt-3 mb-4 pb-4" style="border-bottom:1px solid #e4eef8;">
                        @if($vaga->remuneracao)
                        <div><div style="font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:#6c757d;">Remuneração</div>
                        <div style="font-weight:700;color:#1976d2;font-size:1.1rem;">R$ {{ number_format($vaga->remuneracao, 2, ',', '.') }}</div></div>
                        @endif
                        @if($vaga->local)
                        <div><div style="font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:#6c757d;">Localização</div>
                        <div style="font-weight:600;color:#1a2e44;">{{ $vaga->local }}</div></div>
                        @endif
                        @if($vaga->data_limite)
                        <div><div style="font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:#6c757d;">Prazo</div>
                        <div style="font-weight:600;color:#1a2e44;">{{ \Carbon\Carbon::parse($vaga->data_limite)->format('d/m/Y') }}</div></div>
                        @endif
                        <div><div style="font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:#6c757d;">Publicada</div>
                        <div style="font-weight:600;color:#1a2e44;">{{ \Carbon\Carbon::parse($vaga->data_publicacao)->format('d/m/Y') }}</div></div>
                    </div>

                    <h5 style="font-weight:700;color:#004e8d;margin-bottom:12px;">Descrição da Vaga</h5>
                    <div style="color:#4a5568;line-height:1.8;font-size:.97rem;">{!! nl2br(e($vaga->descricao)) !!}</div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Candidatura Card --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius:18px;border:1.5px solid #d0e6f8!important;">
                <div class="card-body p-4">
                    @auth
                        @if(in_array(auth()->user()->tipo_usuario, ['freelancer','ambos']))
                            @if($jaCandidatou)
                                <div class="alert alert-success mb-0 d-flex align-items-center gap-2" style="border-radius:10px;">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Você já se candidatou a esta vaga!</span>
                                </div>
                            @else
                                <h6 class="fw-bold mb-3" style="color:#1a2e44;">Candidatar-se a esta Vaga</h6>
                                <form method="POST" action="{{ route('candidatura.store') }}">
                                    @csrf
                                    <input type="hidden" name="vaga_id" value="{{ $vaga->id }}">
                                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius:10px;">
                                        <i class="fa-solid fa-paper-plane me-2"></i>Candidatar-me Agora
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="text-muted text-center py-2" style="font-size:.9rem;">
                                <i class="fa-solid fa-info-circle me-1"></i>Apenas freelancers podem se candidatar.
                            </div>
                        @endif
                    @else
                        <h6 class="fw-bold mb-2" style="color:#1a2e44;">Interessado?</h6>
                        <p class="text-muted mb-3" style="font-size:.88rem;">Faça login ou cadastre-se para se candidatar a esta vaga.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2" style="border-radius:10px;">Entrar</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100" style="border-radius:10px;">Criar conta grátis</a>
                    @endauth
                </div>
            </div>

            {{-- Contratante Card --}}
            <div class="card border-0" style="border-radius:18px;background:#f8fbff;border:1.5px solid #d0e6f8!important;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3" style="color:#004e8d;">Publicado por</h6>
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;background:linear-gradient(135deg,#1976d2,#004e8d);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span style="color:white;font-weight:700;font-size:1.1rem;">{{ strtoupper(substr($vaga->usuario->nome ?? 'U', 0, 1)) }}</span>
                        </div>
                        <div>
                            <div class="fw-bold" style="color:#1a2e44;">{{ $vaga->usuario->nome ?? 'Usuário' }}</div>
                            <div style="font-size:.82rem;color:#6c757d;">Contratante</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
