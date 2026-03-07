<?php

namespace App\Http\Controllers;

use App\Models\Candidatura;
use App\Models\Vaga;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CandidaturaController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'vaga_id' => 'required|integer|exists:vaga,id',
        ]);

        $vagaId = (int) $request->vaga_id;
        $userId = auth()->id();

        // Verifica tipo do usuário
        $tipo = auth()->user()->tipo_usuario ?? '';
        if (! in_array(strtolower($tipo), ['freelancer', 'ambos'])) {
            return redirect()->route('vagas.show', $vagaId)
                ->with('error', 'Apenas freelancers podem se candidatar.');
        }

        // Verifica candidatura duplicada
        if (Candidatura::where('id_usuario', $userId)->where('id_vaga', $vagaId)->exists()) {
            return redirect()->route('vagas.show', $vagaId)
                ->with('error', 'Você já se candidatou a esta vaga.');
        }

        Candidatura::create([
            'id_usuario'       => $userId,
            'id_vaga'          => $vagaId,
            'data_candidatura' => now(),
            'status'           => 'pendente',
        ]);

        return redirect()->route('vagas.show', $vagaId)
            ->with('success', 'Candidatura enviada com sucesso!');
    }
}
