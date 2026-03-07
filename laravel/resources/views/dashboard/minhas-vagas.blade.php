@extends('layouts.dashboard')
@section('title', 'Minhas Vagas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 style="font-weight:800;color:#1a2e44;margin-bottom:4px;">Minhas Vagas</h4>
        <p class="text-muted mb-0" style="font-size:.9rem;">Gerencie as vagas que você publicou</p>
    </div>
    <a href="{{ route('vagas.create') }}" class="btn btn-primary" style="border-radius:10px;">
        <i class="fa-solid fa-plus me-2"></i>Nova Vaga
    </a>
</div>

@if($vagas->isEmpty())
    <div class="card border-0" style="border-radius:16px;background:#f8fbff;border:1.5px solid #d0e6f8!important;">
        <div class="card-body text-center py-5">
            <i class="fa-solid fa-briefcase" style="font-size:2.5rem;color:#d0e6f8;"></i>
            <h6 class="mt-3 fw-bold" style="color:#1a2e44;">Você ainda não publicou nenhuma vaga</h6>
            <p class="text-muted mb-3" style="font-size:.9rem;">Publique sua primeira vaga e encontre os melhores freelancers</p>
            <a href="{{ route('vagas.create') }}" class="btn btn-primary" style="border-radius:10px;">
                <i class="fa-solid fa-plus me-2"></i>Publicar Vaga
            </a>
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm" style="border-radius:16px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0" style="border-collapse:separate;">
                    <thead style="background:#f8fbff;">
                        <tr>
                            <th style="padding:14px 20px;font-size:.82rem;color:#6c757d;font-weight:600;border:none;">Título</th>
                            <th style="padding:14px 20px;font-size:.82rem;color:#6c757d;font-weight:600;border:none;">Tipo</th>
                            <th style="padding:14px 20px;font-size:.82rem;color:#6c757d;font-weight:600;border:none;">Publicada em</th>
                            <th style="padding:14px 20px;font-size:.82rem;color:#6c757d;font-weight:600;border:none;">Candidatos</th>
                            <th style="padding:14px 20px;font-size:.82rem;color:#6c757d;font-weight:600;border:none;">Status</th>
                            <th style="padding:14px 20px;font-size:.82rem;color:#6c757d;font-weight:600;border:none;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vagas as $vaga)
                        <tr style="border-top:1px solid #f0f4f8;">
                            <td style="padding:14px 20px;vertical-align:middle;">
                                <div class="fw-bold" style="color:#1a2e44;font-size:.95rem;">{{ $vaga->titulo }}</div>
                                @if($vaga->local)<div style="font-size:.8rem;color:#6c757d;"><i class="fa-solid fa-location-dot me-1"></i>{{ $vaga->local }}</div>@endif
                            </td>
                            <td style="padding:14px 20px;vertical-align:middle;">
                                <span class="badge" style="background:#ecf5ff;color:#1976d2;padding:4px 10px;border-radius:50px;font-size:.78rem;">{{ ucfirst($vaga->tipo_vaga) }}</span>
                            </td>
                            <td style="padding:14px 20px;vertical-align:middle;font-size:.88rem;color:#6c757d;">
                                {{ \Carbon\Carbon::parse($vaga->data_publicacao)->format('d/m/Y') }}
                            </td>
                            <td style="padding:14px 20px;vertical-align:middle;">
                                <a href="{{ route('vagas.candidatos', $vaga->id) }}" style="text-decoration:none;">
                                    <span style="font-weight:700;color:#1976d2;">{{ $vaga->candidaturas_count ?? 0 }}</span>
                                    <span style="font-size:.8rem;color:#6c757d;"> candidatos</span>
                                </a>
                            </td>
                            <td style="padding:14px 20px;vertical-align:middle;">
                                @if($vaga->data_limite && $vaga->data_limite >= now()->toDateString())
                                    <span class="badge bg-success" style="border-radius:50px;font-size:.75rem;">Aberta</span>
                                @else
                                    <span class="badge bg-secondary" style="border-radius:50px;font-size:.75rem;">Encerrada</span>
                                @endif
                            </td>
                            <td style="padding:14px 20px;vertical-align:middle;">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('vagas.show', $vaga->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;" title="Ver"><i class="fa-solid fa-eye"></i></a>
                                    <a href="{{ route('vagas.edit', $vaga->id) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;" title="Editar"><i class="fa-solid fa-pen"></i></a>
                                    <a href="{{ route('vagas.candidatos', $vaga->id) }}" class="btn btn-sm btn-outline-info" style="border-radius:8px;" title="Candidatos"><i class="fa-solid fa-users"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection
