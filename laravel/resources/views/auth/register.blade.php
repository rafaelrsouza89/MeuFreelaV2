@extends('layouts.app')
@section('title', 'Criar Conta')

@section('content')
<div class="container py-5" style="min-height:70vh;">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-sm border-0 p-1">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div style="width:50px;height:50px;background:linear-gradient(135deg,#1976d2,#004e8d);border-radius:13px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                            <i class="fa-solid fa-user-plus" style="color:white;font-size:1rem;"></i>
                        </div>
                        <h4 style="font-weight:800;color:#1a2e44;">Criar Conta</h4>
                        <p class="text-muted" style="font-size:.9rem;">Junte-se ao MeuFreela gratuitamente</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" style="font-size:.88rem;font-weight:600;">Nome Completo</label>
                                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                                       value="{{ old('nome') }}" placeholder="Seu nome completo" required>
                                @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label" style="font-size:.88rem;font-weight:600;">E-mail</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" placeholder="seu@email.com" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:.88rem;font-weight:600;">Senha</label>
                                <input type="password" name="senha" class="form-control @error('senha') is-invalid @enderror"
                                       placeholder="Mínimo 6 caracteres" required>
                                @error('senha')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:.88rem;font-weight:600;">Confirmar Senha</label>
                                <input type="password" name="senha_confirmation" class="form-control" placeholder="Repita a senha" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:.88rem;font-weight:600;">Telefone</label>
                                <input type="text" name="telefone" class="form-control @error('telefone') is-invalid @enderror"
                                       value="{{ old('telefone') }}" placeholder="(47) 99999-9999" required>
                                @error('telefone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:.88rem;font-weight:600;">Tipo de Usuário</label>
                                <select name="tipo_usuario" class="form-select @error('tipo_usuario') is-invalid @enderror" required>
                                    <option value="freelancer" {{ old('tipo_usuario')=='freelancer' ? 'selected' : '' }}>Freelancer</option>
                                    <option value="contratante" {{ old('tipo_usuario')=='contratante' ? 'selected' : '' }}>Contratante</option>
                                    <option value="ambos" {{ old('tipo_usuario')=='ambos' ? 'selected' : '' }}>Ambos</option>
                                </select>
                                @error('tipo_usuario')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius:10px;">
                                    Criar Conta Grátis <i class="fa-solid fa-rocket ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-transparent text-center py-3 border-top-0">
                    <span style="font-size:.88rem;color:#6c757d;">Já tem conta?</span>
                    <a href="{{ route('login') }}" style="font-size:.88rem;color:#1976d2;font-weight:600;text-decoration:none;"> Fazer login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
