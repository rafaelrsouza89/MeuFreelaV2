@extends('layouts.app')
@section('title', 'Recuperar Senha')

@section('content')
<div class="container py-5" style="min-height:70vh;">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow-sm border-0 p-1">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div style="width:50px;height:50px;background:linear-gradient(135deg,#1976d2,#004e8d);border-radius:13px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                            <i class="fa-solid fa-key" style="color:white;font-size:1rem;"></i>
                        </div>
                        <h4 style="font-weight:800;color:#1a2e44;">Recuperar Senha</h4>
                        <p class="text-muted" style="font-size:.9rem;">Insira seu e-mail para receber o link de recuperação</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success py-2 px-3" style="font-size:.9rem;">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label" style="font-size:.88rem;font-weight:600;">E-mail</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="seu@email.com" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius:10px;">
                            Enviar Link de Recuperação
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-transparent text-center py-3 border-top-0">
                    <a href="{{ route('login') }}" style="font-size:.88rem;color:#1976d2;font-weight:600;text-decoration:none;">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar ao login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
