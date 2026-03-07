@extends('layouts.dashboard')
@section('title', 'Meu Painel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 style="font-weight:800;color:#1a2e44;margin-bottom:4px;">Meu Perfil</h4>
        <p class="text-muted mb-0" style="font-size:.9rem;">Atualize suas informações pessoais</p>
    </div>
</div>

{{-- Profile Card --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-4 mb-4">
            <div class="position-relative" id="avatarWrap">
                @if(auth()->user()->foto_perfil)
                    <img src="{{ asset('storage/'.auth()->user()->foto_perfil) }}"
                         id="avatarPreview" alt="Foto"
                         style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #d0e6f8;">
                @else
                    <div id="avatarPreview" style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#1976d2,#004e8d);display:flex;align-items:center;justify-content:center;border:3px solid #d0e6f8;">
                        <span style="color:white;font-size:1.8rem;font-weight:700;">{{ strtoupper(substr(auth()->user()->nome, 0, 1)) }}</span>
                    </div>
                @endif
            </div>
            <div>
                <h5 class="fw-bold mb-1" style="color:#1a2e44;">{{ auth()->user()->nome }}</h5>
                <span class="badge" style="background:#ecf5ff;color:#1976d2;padding:4px 12px;border-radius:50px;font-size:.8rem;">
                    {{ ucfirst(auth()->user()->tipo_usuario) }}
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('dashboard') }}" enctype="multipart/form-data" id="profileForm">
            @csrf @method('PATCH')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.85rem;font-weight:600;">Nome Completo</label>
                    <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                           value="{{ old('nome', auth()->user()->nome) }}" required>
                    @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.85rem;font-weight:600;">Telefone</label>
                    <input type="text" name="telefone" class="form-control @error('telefone') is-invalid @enderror"
                           value="{{ old('telefone', auth()->user()->telefone) }}" placeholder="(47) 99999-9999">
                    @error('telefone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label" style="font-size:.85rem;font-weight:600;">Biografia</label>
                    <textarea name="biografia" rows="3" class="form-control @error('biografia') is-invalid @enderror"
                              placeholder="Fale sobre você, suas habilidades e experiências...">{{ old('biografia', auth()->user()->biografia) }}</textarea>
                    @error('biografia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.85rem;font-weight:600;">Especialidades</label>
                    <input type="text" name="especialidades" class="form-control @error('especialidades') is-invalid @enderror"
                           value="{{ old('especialidades', auth()->user()->especialidades) }}" placeholder="Ex: PHP, Laravel, React">
                    @error('especialidades')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.85rem;font-weight:600;">LinkedIn</label>
                    <input type="url" name="linkedin" class="form-control @error('linkedin') is-invalid @enderror"
                           value="{{ old('linkedin', auth()->user()->linkedin) }}" placeholder="https://linkedin.com/in/...">
                    @error('linkedin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.85rem;font-weight:600;">Portfólio URL</label>
                    <input type="url" name="portfolio_url" class="form-control @error('portfolio_url') is-invalid @enderror"
                           value="{{ old('portfolio_url', auth()->user()->portfolio_url) }}" placeholder="https://seusite.com.br">
                    @error('portfolio_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.85rem;font-weight:600;">Foto de Perfil</label>
                    <input type="file" name="foto_perfil" id="fotoInput" class="form-control @error('foto_perfil') is-invalid @enderror" accept="image/*">
                    @error('foto_perfil')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <hr style="border-color:#e4eef8;">
                    <h6 class="fw-bold mb-3" style="color:#004e8d;">Endereço</h6>
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:.85rem;font-weight:600;">CEP</label>
                    <input type="text" name="cep" class="form-control" value="{{ old('cep', auth()->user()->cep) }}" placeholder="89250-000">
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:.85rem;font-weight:600;">Estado</label>
                    <input type="text" name="estado" class="form-control" value="{{ old('estado', auth()->user()->estado) }}" placeholder="SC">
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:.85rem;font-weight:600;">Cidade</label>
                    <input type="text" name="cidade" class="form-control" value="{{ old('cidade', auth()->user()->cidade) }}" placeholder="Jaraguá do Sul">
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:.85rem;font-weight:600;">Bairro</label>
                    <input type="text" name="bairro" class="form-control" value="{{ old('bairro', auth()->user()->bairro) }}">
                </div>
                <div class="col-md-9">
                    <label class="form-label" style="font-size:.85rem;font-weight:600;">Logradouro</label>
                    <input type="text" name="logradouro" class="form-control" value="{{ old('logradouro', auth()->user()->logradouro) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:.85rem;font-weight:600;">Número</label>
                    <input type="text" name="numero" class="form-control" value="{{ old('numero', auth()->user()->numero) }}">
                </div>
                <div class="col-12 d-flex justify-content-end pt-2">
                    <button type="submit" class="btn btn-primary px-4" style="border-radius:10px;">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Salvar Alterações
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('fotoInput')?.addEventListener('change', function(e){
    const file = e.target.files[0];
    if(!file) return;
    const reader = new FileReader();
    reader.onload = function(ev){
        const prev = document.getElementById('avatarPreview');
        if(prev.tagName === 'IMG'){ prev.src = ev.target.result; }
        else {
            const img = document.createElement('img');
            img.id = 'avatarPreview';
            img.src = ev.target.result;
            img.style.cssText = 'width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #d0e6f8;';
            prev.replaceWith(img);
        }
    };
    reader.readAsDataURL(file);
});
</script>
@endsection
