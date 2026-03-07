@extends('layouts.app')
@section('title', 'Entrar')

@section('content')
<div class="container py-5" style="min-height:70vh;">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow-sm border-0 p-1">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div style="width:50px;height:50px;background:linear-gradient(135deg,#1976d2,#004e8d);border-radius:13px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                            <i class="fa-solid fa-bolt" style="color:white;font-size:1.1rem;"></i>
                        </div>
                        <h4 style="font-weight:800;color:#1a2e44;">Acessar Conta</h4>
                        <p class="text-muted" style="font-size:.9rem;">Bem-vindo de volta ao MeuFreela</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success py-2 px-3" style="font-size:.9rem;">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-600" style="font-size:.88rem;font-weight:600;">E-mail</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="seu@email.com" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label mb-0" style="font-size:.88rem;font-weight:600;">Senha</label>
                                <a href="{{ route('password.request') }}" style="font-size:.82rem;color:#1976d2;">Esqueceu?</a>
                            </div>
                            <input type="password" name="senha" class="form-control" placeholder="••••••" required>
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input mt-0">
                            <label for="remember" style="font-size:.88rem;color:#6c757d;">Lembrar de mim</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius:10px;">
                            Entrar <i class="fa-solid fa-arrow-right ms-1"></i>
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-transparent text-center py-3 border-top-0">
                    <span style="font-size:.88rem;color:#6c757d;">Não tem conta?</span>
                    <a href="{{ route('register') }}" style="font-size:.88rem;color:#1976d2;font-weight:600;text-decoration:none;"> Cadastre-se grátis</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
