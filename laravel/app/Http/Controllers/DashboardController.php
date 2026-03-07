<?php

namespace App\Http\Controllers;

use App\Models\Candidatura;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard.index', ['user' => auth()->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $validated = $request->validate([
            'nome'          => 'required|string|max:100',
            'telefone'      => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'cpf'           => 'nullable|string|max:20',
            'linkedin'      => 'nullable|url|max:255',
            'cep'           => 'nullable|string|max:10',
            'estado'        => 'nullable|string|max:50',
            'cidade'        => 'nullable|string|max:100',
            'bairro'        => 'nullable|string|max:100',
            'logradouro'    => 'nullable|string|max:255',
            'numero'        => 'nullable|string|max:20',
            'biografia'     => 'nullable|string',
            'especialidades' => 'nullable|string|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'foto_perfil'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload da foto
        if ($request->hasFile('foto_perfil')) {
            // Remove foto antiga
            if ($user->foto_perfil && Storage::disk('public')->exists($user->foto_perfil)) {
                Storage::disk('public')->delete($user->foto_perfil);
            }
            $path = $request->file('foto_perfil')->store('fotos', 'public');
            $validated['foto_perfil'] = $path;
        }

        $user->update($validated);

        session(['user_name' => $user->nome]);

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    public function minhasVagas(): View
    {
        /** @var User $user */
        $user  = auth()->user();
        $vagas = $user->vagas()->withCount('candidaturas')->orderByDesc('data_publicacao')->get();

        return view('dashboard.minhas-vagas', compact('vagas'));
    }

    public function minhasCandidaturas(): View
    {
        /** @var User $user */
        $user = auth()->user();
        $candidaturas = $user->candidaturas()
            ->with(['vaga.usuario'])
            ->orderByDesc('data_candidatura')
            ->get();

        return view('dashboard.minhas-candidaturas', compact('candidaturas'));
    }
}
