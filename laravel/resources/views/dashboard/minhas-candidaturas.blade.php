@extends('layouts.dashboard')
@section('title', 'Minhas Candidaturas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 style="font-weight:800;color:#1a2e44;margin-bottom:4px;">Minhas Candidaturas</h4>
        <p class="text-muted mb-0" style="font-size:.9rem;">Acompanhe o status das suas candidaturas</p>
    </div>
    <a href="{{ route('vagas.index') }}" class="btn btn-outline-primary" style="border-radius:10px;">
        <i class="fa-solid fa-search me-2"></i>Buscar Vagas
    </a>
</div>

@if($candidaturas->isEmpty())
    <div class="card border-0" style="border-radius:16px;background:#f8fbff;border:1.5px solid #d0e6f8!important;">
        <div class="card-body text-center py-5">
            <i class="fa-solid fa-paper-plane" style="font-size:2.5rem;color:#d0e6f8;"></i>
            <h6 class="mt-3 fw-bold" style="color:#1a2e44;">Você ainda não se candidatou a nenhuma vaga</h6>
            <p class="text-muted mb-3" style="font-size:.9rem;">Explore as vagas disponíveis e candidate-se agora</p>
            <a href="{{ route('vagas.index') }}" class="btn btn-primary" style="border-radius:10px;">
                <i class="fa-solid fa-search me-2"></i>Ver Vagas Disponíveis
            </a>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach($candidaturas as $candidatura)
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius:14px;border:1.5px solid #e4eef8!important;">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h6 class="fw-bold mb-0" style="color:#1a2e44;">{{ $candidatura->vaga->titulo ?? 'Vaga removida' }}</h6>
                                @php
                                    $statusColors = ['pendente'=>'#ffc107','aceito'=>'#28a745','recusado'=>'#dc3545'];
                                    $statusLabels = ['pendente'=>'Pendente','aceito'=>'Aceito','recusado'=>'Recusado'];
                                    $color = $statusColors[$candidatura->status] ?? '#6c757d';
                                    $label = $statusLabels[$candidatura->status] ?? ucfirst($candidatura->status);
                                @endphp
                                <span class="badge" style="background:{{ $color }}22;color:{{ $color }};padding:3px 10px;border-radius:50px;font-size:.75rem;font-weight:600;border:1px solid {{ $color }}44;">
                                    {{ $label }}
                                </span>
                            </div>
                            @if($candidatura->vaga)
                                <div class="d-flex flex-wrap gap-3 mt-1">
                                    <span style="font-size:.83rem;color:#6c757d;"><i class="fa-solid fa-briefcase me-1"></i>{{ ucfirst($candidatura->vaga->tipo_vaga) }}</span>
                                    @if($candidatura->vaga->local)<span style="font-size:.83rem;color:#6c757d;"><i class="fa-solid fa-location-dot me-1"></i>{{ $candidatura->vaga->local }}</span>@endif
                                    @if($candidatura->vaga->remuneracao)<span style="font-size:.83rem;color:#1976d2;"><i class="fa-solid fa-coins me-1"></i>R$ {{ number_format($candidatura->vaga->remuneracao,2,',','.') }}</span>@endif
                                </div>
                            @endif
                        </div>
                        <div class="text-end">
                            <div style="font-size:.78rem;color:#6c757d;">Candidatou-se em</div>
                            <div style="font-size:.85rem;font-weight:600;color:#1a2e44;">{{ \Carbon\Carbon::parse($candidatura->data_candidatura)->format('d/m/Y') }}</div>
                            @if($candidatura->vaga)
                                <a href="{{ route('vagas.show', $candidatura->vaga->id) }}" class="btn btn-sm btn-outline-primary mt-2" style="border-radius:8px;font-size:.8rem;">Ver vaga</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection
