@extends('layouts.dashboard')
@section('title', 'Candidatos - '.$vaga->titulo)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('minhas-vagas') }}" style="font-size:.85rem;color:#1976d2;text-decoration:none;"><i class="fa-solid fa-arrow-left me-1"></i>Minhas Vagas</a>
        <h4 style="font-weight:800;color:#1a2e44;margin-bottom:4px;margin-top:8px;">Candidatos</h4>
        <p class="text-muted mb-0" style="font-size:.9rem;">Vaga: <strong>{{ $vaga->titulo }}</strong></p>
    </div>
    <span class="badge" style="background:#ecf5ff;color:#1976d2;padding:8px 16px;border-radius:50px;font-size:.88rem;font-weight:600;">
        {{ $candidaturas->count() }} candidato(s)
    </span>
</div>

@if($candidaturas->isEmpty())
    <div class="card border-0" style="border-radius:16px;background:#f8fbff;border:1.5px solid #d0e6f8!important;">
        <div class="card-body text-center py-5">
            <i class="fa-solid fa-users" style="font-size:2.5rem;color:#d0e6f8;"></i>
            <h6 class="mt-3 fw-bold" style="color:#1a2e44;">Nenhum candidato ainda</h6>
            <p class="text-muted mb-0" style="font-size:.9rem;">Quando freelancers se candidatarem, aparecerão aqui</p>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach($candidaturas as $cand)
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius:14px;border:1.5px solid #e4eef8!important;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#1976d2,#004e8d);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            @if($cand->usuario && $cand->usuario->foto_perfil)
                                <img src="{{ asset('storage/'.$cand->usuario->foto_perfil) }}" style="width:52px;height:52px;border-radius:50%;object-fit:cover;" alt="">
                            @else
                                <span style="color:white;font-weight:700;font-size:1.2rem;">{{ strtoupper(substr($cand->usuario->nome ?? 'U', 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold" style="color:#1a2e44;">{{ $cand->usuario->nome ?? 'Usuário' }}</div>
                            <div style="font-size:.82rem;color:#6c757d;">{{ $cand->usuario->email ?? '' }}</div>
                        </div>
                        @php $statusColors=['pendente'=>'#ffc107','aceito'=>'#28a745','recusado'=>'#dc3545']; $color=$statusColors[$cand->status]??'#6c757d'; @endphp
                        <span class="badge" style="background:{{ $color }}22;color:{{ $color }};padding:4px 12px;border-radius:50px;font-size:.75rem;font-weight:600;border:1px solid {{ $color }}44;">
                            {{ ucfirst($cand->status) }}
                        </span>
                    </div>

                    @if($cand->usuario && $cand->usuario->especialidades)
                        <div class="mb-3">
                            <div style="font-size:.78rem;font-weight:600;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Especialidades</div>
                            <div style="font-size:.87rem;color:#4a5568;">{{ $cand->usuario->especialidades }}</div>
                        </div>
                    @endif
                    @if($cand->usuario && $cand->usuario->biografia)
                        <p style="font-size:.85rem;color:#6c757d;line-height:1.6;margin-bottom:12px;">{{ Str::limit($cand->usuario->biografia, 100) }}</p>
                    @endif

                    <div class="d-flex gap-2 flex-wrap">
                        @if($cand->usuario)
                            <a href="{{ route('perfil.show', $cand->usuario->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:.82rem;">
                                <i class="fa-solid fa-user me-1"></i>Ver Perfil
                            </a>
                        @endif
                        @if($cand->usuario && $cand->usuario->linkedin)
                            <a href="{{ $cand->usuario->linkedin }}" target="_blank" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-size:.82rem;">
                                <i class="fa-brands fa-linkedin me-1"></i>LinkedIn
                            </a>
                        @endif
                        <small class="text-muted align-self-center ms-auto" style="font-size:.78rem;">
                            {{ \Carbon\Carbon::parse($cand->data_candidatura)->format('d/m/Y') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection
