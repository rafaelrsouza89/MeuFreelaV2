<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaga extends Model
{
    protected $table = 'vaga';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario', 'titulo', 'descricao', 'tipo_vaga',
        'remuneracao', 'local', 'data_publicacao', 'data_limite',
    ];

    protected function casts(): array
    {
        return [
            'data_publicacao' => 'datetime',
            'data_limite'     => 'date',
            'remuneracao'     => 'decimal:2',
        ];
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function candidaturas()
    {
        return $this->hasMany(Candidatura::class, 'id_vaga');
    }
}
