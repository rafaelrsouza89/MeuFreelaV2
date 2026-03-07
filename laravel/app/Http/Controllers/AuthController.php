<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /* ──────────────────── LOGIN ──────────────────── */

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->senha], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['email' => 'E-mail ou senha inválidos.'])->onlyInput('email');
    }

    /* ──────────────────── REGISTER ──────────────────── */

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'nome'         => 'required|string|max:100',
            'email'        => 'required|email|max:100|unique:usuario,email',
            'senha'        => 'required|string|min:6|confirmed',
            'telefone'     => 'required|string|max:20',
            'tipo_usuario' => 'required|in:freelancer,contratante,ambos',
        ]);

        User::create([
            'nome'          => $request->nome,
            'email'         => $request->email,
            'senha'         => Hash::make($request->senha),
            'telefone'      => $request->telefone,
            'tipo_usuario'  => $request->tipo_usuario,
            'data_cadastro' => now(),
        ]);

        return redirect()->route('login')->with('success', 'Cadastro realizado com sucesso! Faça seu login.');
    }

    /* ──────────────────── LOGOUT ──────────────────── */

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    /* ──────────────────── RECUPERAR SENHA ──────────────────── */

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token      = bin2hex(random_bytes(32));
            $tokenHash  = hash('sha256', $token);

            $user->update([
                'reset_token_hash'       => $tokenHash,
                'reset_token_expires_at' => now()->addHour(),
            ]);

            $link = route('password.reset', ['token' => $token]);

            // Em dev, loga o link no log do Laravel
            \Log::info("MeuFreela reset senha link: {$link}");

            // Em produção: mail($user->email, 'Recuperação de Senha', "Acesse: {$link}");
        }

        return back()->with('success', 'Se o e-mail existir, um link de recuperação foi enviado.');
    }

    /* ──────────────────── RESETAR SENHA ──────────────────── */

    public function showResetPassword(Request $request, string $token): View
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token'                 => 'required',
            'senha'                 => 'required|min:6|confirmed',
        ]);

        $tokenHash = hash('sha256', $request->token);
        $user      = User::where('reset_token_hash', $tokenHash)
                         ->where('reset_token_expires_at', '>', now())
                         ->first();

        if (! $user) {
            return back()->withErrors(['token' => 'Link de recuperação inválido ou expirado.']);
        }

        $user->update([
            'senha'                  => Hash::make($request->senha),
            'reset_token_hash'       => null,
            'reset_token_expires_at' => null,
        ]);

        return redirect()->route('login')->with('success', 'Senha redefinida com sucesso!');
    }
}
