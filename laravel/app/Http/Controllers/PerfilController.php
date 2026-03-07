<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class PerfilController extends Controller
{
    public function show(int $id): View
    {
        $freelancer = User::where('id', $id)
            ->whereIn('tipo_usuario', ['freelancer', 'ambos'])
            ->firstOrFail();

        return view('perfil.show', compact('freelancer'));
    }
}
