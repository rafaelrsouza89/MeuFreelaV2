@extends('layouts.app')
@section('title', 'Vagas Disponíveis')

@section('content')
<div style="background:#ecf5ff;border-bottom:1px solid #d0e6f8;padding:40px 0;">
    <div class="container">
        <h1 style="font-weight:800;color:#004e8d;font-size:1.8rem;margin-bottom:6px;">Vagas Disponíveis</h1>
        <p class="text-muted mb-0">Encontre a oportunidade ideal para você</p>
    </div>
</div>

<div class="container py-5">
    {{-- Filters --}}
    <form method="GET" action="{{ route('vagas.index') }}" class="row g-3 mb-4 align-items-end">
        <div class="col-md-6">
            <label class="form-label" style="font-size:.85rem;font-weight:600;">Buscar</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Título, descrição...">
        </div>
        <div class="col-md-3">
            <label class="form-label" style="font-size:.85rem;font-weight:600;">Tipo</label>
            <select name="tipo" class="form-select">
                <option value="">Todos os tipos</option>
                <option value="remoto" {{ request('tipo')=='remoto' ? 'selected' : '' }}>Remoto</option>
                <option value="presencial" {{ request('tipo')=='presencial' ? 'selected' : '' }}>Presencial</option>
                <option value="hibrido" {{ request('tipo')=='hibrido' ? 'selected' : '' }}>Híbrido</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="fa-solid fa-search me-1"></i>Filtrar</button>
            <a href="{{ route('vagas.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-xmark"></i></a>
        </div>
    </form>

    {{-- Results --}}
    @if($vagas->isEmpty())
        <div class="text-center py-5">
            <i class="fa-solid fa-briefcase" style="font-size:3rem;color:#d0e6f8;"></i>
            <h5 class="mt-3 text-muted">Nenhuma vaga encontrada</h5>
            <p class="text-muted">Tente mudar os filtros ou <a href="{{ route('vagas.index') }}">ver todas as vagas</a>.</p>
        </div>
    @else
        <div class="mb-3" style="font-size:.9rem;color:#6c757d;">{{ $vagas->total() }} vaga(s) encontrada(s)</div>
        <div class="row g-4">
            @foreach($vagas as $vaga)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 h-100" style="border:1.5px solid #d0e6f8!important;border-radius:16px;transition:transform .2s,box-shadow .2s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(25,118,210,.1)';" onmouseout="this.style.transform='';this.style.boxShadow='';">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge" style="background:#ecf5ff;color:#1976d2;font-size:.78rem;padding:5px 12px;border-radius:50px;font-weight:600;">
                                {{ ucfirst($vaga->tipo_vaga) }}
                            </span>
                            @if($vaga->data_limite && $vaga->data_limite >= now()->toDateString())
                                <span class="badge bg-success" style="font-size:.75rem;border-radius:50px;">Aberta</span>
                            @else
                                <span class="badge bg-secondary" style="font-size:.75rem;border-radius:50px;">Encerrada</span>
                            @endif
                        </div>
                        <h5 class="fw-bold mb-1" style="color:#1a2e44;font-size:1rem;">{{ $vaga->titulo }}</h5>
                        <p class="text-muted mb-3" style="font-size:.88rem;line-height:1.6;">{{ Str::limit($vaga->descricao, 100) }}</p>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($vaga->remuneracao)
                                <span style="font-size:.82rem;color:#1976d2;"><i class="fa-solid fa-coins me-1"></i>R$ {{ number_format($vaga->remuneracao, 2, ',', '.') }}</span>
                            @endif
                            @if($vaga->local)
                                <span style="font-size:.82rem;color:#6c757d;"><i class="fa-solid fa-location-dot me-1"></i>{{ $vaga->local }}</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted" style="font-size:.78rem;">
                                <i class="fa-regular fa-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($vaga->data_publicacao)->diffForHumans() }}
                            </small>
                            <a href="{{ route('vagas.show', $vaga->id) }}" class="btn btn-sm btn-primary" style="border-radius:50px;padding:5px 16px;font-size:.82rem;">Ver vaga</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $vagas->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
