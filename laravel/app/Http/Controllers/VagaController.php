<?php

namespace App\Http\Controllers;

use App\Models\Candidatura;
use App\Models\Vaga;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VagaController extends Controller
{
    /* ──────────────────── PUBLIC ──────────────────── */

    public function index(Request $request): View
    {
        $query = Vaga::query()
            ->with('usuario')
            ->where(function($q) {
                $q->whereNull('data_limite')->orWhere('data_limite', '>=', today());
            });

        if ($q = $request->input('q')) {
            $query->where(function ($qb) use ($q) {
                $qb->where('titulo', 'like', "%{$q}%")
                   ->orWhere('descricao', 'like', "%{$q}%");
            });
        }

        if ($tipo = $request->input('tipo')) {
            if (in_array($tipo, ['remoto', 'presencial', 'hibrido'])) {
                $query->where('tipo_vaga', $tipo);
            }
        }

        $vagas = $query->orderByDesc('data_publicacao')->paginate(12);

        return view('vagas.index', compact('vagas'));
    }

    public function show(int $id): View
    {
        $vaga = Vaga::with('usuario')->findOrFail($id);

        $jaCandidatou = false;
        if (auth()->check()) {
            $jaCandidatou = Candidatura::where('id_usuario', auth()->id())
                ->where('id_vaga', $id)
                ->exists();
        }

        return view('vagas.show', compact('vaga', 'jaCandidatou'));
    }

    /* ──────────────────── CRUD (auth) ──────────────────── */

    public function create(): View
    {
        $this->authorizeContratante();
        return view('vagas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeContratante();

        $validated = $request->validate([
            'titulo'      => 'required|string|max:100',
            'descricao'   => 'required|string',
            'tipo_vaga'   => 'required|in:remoto,presencial,hibrido',
            'remuneracao' => 'nullable|numeric|min:0',
            'local'       => 'nullable|string|max:255',
            'data_limite' => 'nullable|date|after_or_equal:today',
        ]);

        Vaga::create(array_merge($validated, [
            'id_usuario'      => auth()->id(),
            'data_publicacao' => now(),
        ]));

        return redirect()->route('minhas-vagas')->with('success', 'Vaga publicada com sucesso!');
    }

    public function edit(int $id): View
    {
        $vaga = Vaga::where('id', $id)->where('id_usuario', auth()->id())->firstOrFail();
        return view('vagas.edit', compact('vaga'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $vaga = Vaga::where('id', $id)->where('id_usuario', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'titulo'      => 'required|string|max:100',
            'descricao'   => 'required|string',
            'tipo_vaga'   => 'required|in:remoto,presencial,hibrido',
            'remuneracao' => 'nullable|numeric|min:0',
            'local'       => 'nullable|string|max:255',
            'data_limite' => 'nullable|date',
        ]);

        $vaga->update($validated);

        return redirect()->route('minhas-vagas')->with('success', 'Vaga atualizada com sucesso!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $vaga = Vaga::where('id', $id)->where('id_usuario', auth()->id())->firstOrFail();
        $vaga->candidaturas()->delete();
        $vaga->delete();

        return redirect()->route('minhas-vagas')->with('success', 'Vaga excluída com sucesso!');
    }

    public function verCandidatos(int $vagaId): View
    {
        $vaga = Vaga::where('id', $vagaId)->where('id_usuario', auth()->id())->firstOrFail();
        $candidaturas = $vaga->candidaturas()->with('usuario')->orderByDesc('data_candidatura')->get();

        return view('dashboard.ver-candidatos', compact('vaga', 'candidaturas'));
    }

    /* ──────────────────── HELPER ──────────────────── */

    private function authorizeContratante(): void
    {
        $tipo = auth()->user()->tipo_usuario ?? '';
        if (! in_array($tipo, ['contratante', 'ambos'])) {
            abort(403, 'Acesso não autorizado.');
        }
    }
}
