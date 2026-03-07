@extends('layouts.app')
@section('title', 'Editar Vaga')

@section('content')
<div style="background:#ecf5ff;border-bottom:1px solid #d0e6f8;padding:40px 0;">
    <div class="container">
        <h1 style="font-weight:800;color:#004e8d;font-size:1.8rem;">Editar Vaga</h1>
        <p class="text-muted mb-0">Atualize as informações da sua vaga</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius:18px;">
                <div class="card-body p-4 p-lg-5">
                    <form method="POST" action="{{ route('vagas.update', $vaga->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label" style="font-weight:600;font-size:.9rem;">Título da Vaga <span class="text-danger">*</span></label>
                                <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror"
                                       value="{{ old('titulo', $vaga->titulo) }}" required>
                                @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label" style="font-weight:600;font-size:.9rem;">Descrição <span class="text-danger">*</span></label>
                                <textarea name="descricao" rows="6" class="form-control @error('descricao') is-invalid @enderror" required>{{ old('descricao', $vaga->descricao) }}</textarea>
                                @error('descricao')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:600;font-size:.9rem;">Tipo de Vaga <span class="text-danger">*</span></label>
                                <select name="tipo_vaga" class="form-select @error('tipo_vaga') is-invalid @enderror" required>
                                    <option value="remoto" {{ old('tipo_vaga',$vaga->tipo_vaga)=='remoto' ? 'selected' : '' }}>Remoto</option>
                                    <option value="presencial" {{ old('tipo_vaga',$vaga->tipo_vaga)=='presencial' ? 'selected' : '' }}>Presencial</option>
                                    <option value="hibrido" {{ old('tipo_vaga',$vaga->tipo_vaga)=='hibrido' ? 'selected' : '' }}>Híbrido</option>
                                </select>
                                @error('tipo_vaga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:600;font-size:.9rem;">Remuneração (R$)</label>
                                <input type="number" name="remuneracao" step="0.01" min="0" class="form-control @error('remuneracao') is-invalid @enderror"
                                       value="{{ old('remuneracao', $vaga->remuneracao) }}">
                                @error('remuneracao')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:600;font-size:.9rem;">Localização</label>
                                <input type="text" name="local" class="form-control @error('local') is-invalid @enderror"
                                       value="{{ old('local', $vaga->local) }}">
                                @error('local')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight:600;font-size:.9rem;">Prazo (Data Limite)</label>
                                <input type="date" name="data_limite" class="form-control @error('data_limite') is-invalid @enderror"
                                       value="{{ old('data_limite', $vaga->data_limite ? \Carbon\Carbon::parse($vaga->data_limite)->format('Y-m-d') : '') }}">
                                @error('data_limite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 d-flex gap-3 justify-content-between pt-2">
                                <form method="POST" action="{{ route('vagas.destroy', $vaga->id) }}"
                                      onsubmit="return confirm('Tem certeza que deseja excluir esta vaga?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" style="border-radius:10px;">
                                        <i class="fa-solid fa-trash me-1"></i>Excluir Vaga
                                    </button>
                                </form>
                                <div class="d-flex gap-3">
                                    <a href="{{ route('minhas-vagas') }}" class="btn btn-outline-secondary" style="border-radius:10px;">Cancelar</a>
                                    <button type="submit" class="btn btn-primary px-4" style="border-radius:10px;">
                                        <i class="fa-solid fa-floppy-disk me-2"></i>Salvar Alterações
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
