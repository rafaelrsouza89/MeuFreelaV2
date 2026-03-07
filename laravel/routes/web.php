<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidaturaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\VagaController;
use Illuminate\Support\Facades\Route;

/* ── PÚBLICA ──────────────────────────────────────── */
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/vagas', [VagaController::class, 'index'])->name('vagas.index');
Route::get('/vagas/{id}', [VagaController::class, 'show'])->name('vagas.show');

Route::get('/perfil/{id}', [PerfilController::class, 'show'])->name('perfil.show');

/* ── AUTH (guest only) ────────────────────────────── */
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);

    Route::get('/cadastro',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/cadastro', [AuthController::class, 'register']);

    Route::get('/recuperar-senha',  [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/recuperar-senha', [AuthController::class, 'forgotPassword'])->name('password.email');

    Route::get('/resetar-senha/{token}',  [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/resetar-senha',         [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/* ── PROTEGIDAS (auth) ────────────────────────────── */
Route::middleware('auth')->group(function () {

    Route::get('/dashboard',    [DashboardController::class, 'index'])->name('dashboard');
    Route::patch('/dashboard',  [DashboardController::class, 'update'])->name('dashboard.update');

    Route::get('/minhas-vagas',          [DashboardController::class, 'minhasVagas'])->name('minhas-vagas');
    Route::get('/minhas-candidaturas',   [DashboardController::class, 'minhasCandidaturas'])->name('minhas-candidaturas');

    // Vagas CRUD
    Route::get('/publicar-vaga',           [VagaController::class, 'create'])->name('vagas.create');
    Route::post('/publicar-vaga',          [VagaController::class, 'store'])->name('vagas.store');
    Route::get('/editar-vaga/{id}',        [VagaController::class, 'edit'])->name('vagas.edit');
    Route::patch('/editar-vaga/{id}',      [VagaController::class, 'update'])->name('vagas.update');
    Route::delete('/excluir-vaga/{id}',    [VagaController::class, 'destroy'])->name('vagas.destroy');

    // Candidatura
    Route::post('/candidatura', [CandidaturaController::class, 'store'])->name('candidatura.store');

    // Ver candidatos
    Route::get('/candidatos/{vagaId}', [VagaController::class, 'verCandidatos'])->name('vagas.candidatos');
});
