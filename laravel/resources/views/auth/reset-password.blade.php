@extends('layouts.app')
@section('title', 'Redefinir Senha')

@section('content')
<div class="container py-5" style="min-height:70vh;">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow-sm border-0 p-1">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div style="width:50px;height:50px;background:linear-gradient(135deg,#1976d2,#004e8d);border-radius:13px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                            <i class="fa-solid fa-lock-open" style="color:white;font-size:1rem;"></i>
                        </div>
                        <h4 style="font-weight:800;color:#1a2e44;">Nova Senha</h4>
                        <p class="text-muted" style="font-size:.9rem;">Defina sua nova senha de acesso</p>
                    </div>

                    @if($errors->has('token'))
                        <div class="alert alert-danger py-2 px-3" style="font-size:.9rem;">{{ $errors->first('token') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="mb-3">
                            <label class="form-label" style="font-size:.88rem;font-weight:600;">Nova Senha</label>
                            <input type="password" name="senha" class="form-control @error('senha') is-invalid @enderror"
                                   placeholder="Mínimo 6 caracteres" required>
                            @error('senha')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label" style="font-size:.88rem;font-weight:600;">Confirmar Nova Senha</label>
                            <input type="password" name="senha_confirmation" class="form-control" placeholder="Repita a senha" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius:10px;">
                            Redefinir Senha
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
