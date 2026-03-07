<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuario';

    public $timestamps = false;

    protected $fillable = [
        'nome', 'email', 'senha', 'telefone', 'tipo_usuario',
        'data_cadastro', 'biografia', 'especialidades', 'portfolio_url',
        'data_nascimento', 'cpf', 'linkedin', 'cep', 'estado', 'cidade',
        'bairro', 'logradouro', 'numero', 'foto_perfil',
        'reset_token_hash', 'reset_token_expires_at',
    ];

    protected $hidden = ['senha', 'remember_token', 'reset_token_hash'];

    protected function casts(): array
    {
        return [
            'reset_token_expires_at' => 'datetime',
            'data_nascimento' => 'date',
        ];
    }

    /** Laravel usa este método para verificar a senha no Auth::attempt */
    public function getAuthPasswordName(): string
    {
        return 'senha';
    }

    public function getAuthPassword(): string
    {
        return $this->senha;
    }

    public function vagas()
    {
        return $this->hasMany(Vaga::class, 'id_usuario');
    }

    public function candidaturas()
    {
        return $this->hasMany(Candidatura::class, 'id_usuario');
    }
}
