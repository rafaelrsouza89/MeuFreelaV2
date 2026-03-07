<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidatura extends Model
{
    protected $table = 'candidatura';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario', 'id_vaga', 'data_candidatura', 'status',
    ];

    protected function casts(): array
    {
        return [
            'data_candidatura' => 'datetime',
        ];
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function vaga()
    {
        return $this->belongsTo(Vaga::class, 'id_vaga');
    }
}
