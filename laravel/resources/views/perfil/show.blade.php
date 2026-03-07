@extends('layouts.app')
@section('title', $freelancer->nome)

@section('content')
<div style="background:#ecf5ff;border-bottom:1px solid #d0e6f8;padding:40px 0;">
    <div class="container">
        <a href="javascript:history.back()" style="font-size:.88rem;color:#1976d2;text-decoration:none;"><i class="fa-solid fa-arrow-left me-1"></i>Voltar</a>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        {{-- Left: Profile Info --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm text-center" style="border-radius:18px;">
                <div class="card-body p-4">
                    @if($freelancer->foto_perfil)
                        <img src="{{ asset('storage/'.$freelancer->foto_perfil) }}" alt="{{ $freelancer->nome }}"
                             style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:4px solid #d0e6f8;margin-bottom:16px;">
                    @else
                        <div style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,#1976d2,#004e8d);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                            <span style="color:white;font-size:2.2rem;font-weight:700;">{{ strtoupper(substr($freelancer->nome, 0, 1)) }}</span>
                        </div>
                    @endif
                    <h5 class="fw-bold mb-1" style="color:#1a2e44;">{{ $freelancer->nome }}</h5>
                    <span class="badge mb-3" style="background:#ecf5ff;color:#1976d2;padding:5px 14px;border-radius:50px;font-size:.82rem;">Freelancer</span>

                    @if($freelancer->cidade || $freelancer->estado)
                        <div class="text-muted mb-3" style="font-size:.88rem;">
                            <i class="fa-solid fa-location-dot me-1"></i>
                            {{ implode(', ', array_filter([$freelancer->cidade, $freelancer->estado])) }}
                        </div>
                    @endif

                    <div class="d-flex flex-column gap-2">
                        @if($freelancer->linkedin)
                            <a href="{{ $freelancer->linkedin }}" target="_blank" class="btn btn-outline-primary" style="border-radius:10px;font-size:.88rem;">
                                <i class="fa-brands fa-linkedin me-2"></i>LinkedIn
                            </a>
                        @endif
                        @if($freelancer->portfolio_url)
                            <a href="{{ $freelancer->portfolio_url }}" target="_blank" class="btn btn-outline-secondary" style="border-radius:10px;font-size:.88rem;">
                                <i class="fa-solid fa-globe me-2"></i>Portfólio
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Details --}}
        <div class="col-lg-8">
            @if($freelancer->biografia)
            <div class="card border-0 shadow-sm mb-4" style="border-radius:18px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3" style="color:#004e8d;"><i class="fa-solid fa-user me-2"></i>Sobre Mim</h6>
                    <p style="color:#4a5568;line-height:1.8;font-size:.97rem;margin:0;">{{ $freelancer->biografia }}</p>
                </div>
            </div>
            @endif

            @if($freelancer->especialidades)
            <div class="card border-0 shadow-sm mb-4" style="border-radius:18px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3" style="color:#004e8d;"><i class="fa-solid fa-star me-2"></i>Especialidades</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(explode(',', $freelancer->especialidades) as $esp)
                            <span style="background:#ecf5ff;color:#1976d2;padding:5px 14px;border-radius:50px;font-size:.85rem;font-weight:600;border:1px solid #d0e6f8;">
                                {{ trim($esp) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <div class="card border-0 shadow-sm" style="border-radius:18px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3" style="color:#004e8d;"><i class="fa-solid fa-info-circle me-2"></i>Informações</h6>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div style="font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.8px;color:#6c757d;">Membro desde</div>
                            <div style="font-weight:600;color:#1a2e44;">
                                {{ $freelancer->data_cadastro ? \Carbon\Carbon::parse($freelancer->data_cadastro)->format('M/Y') : 'N/A' }}
                            </div>
                        </div>
                        @if($freelancer->telefone)
                        <div class="col-sm-6">
                            <div style="font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.8px;color:#6c757d;">Telefone</div>
                            <div style="font-weight:600;color:#1a2e44;">{{ $freelancer->telefone }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
