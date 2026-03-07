<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidatura', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuario')->onDelete('cascade');
            $table->foreignId('id_vaga')->constrained('vaga')->onDelete('cascade');
            $table->dateTime('data_candidatura')->useCurrent();
            $table->enum('status', ['pendente', 'aceita', 'recusada'])->default('pendente');
            $table->unique(['id_usuario', 'id_vaga'], 'uk_candidatura_usuario_vaga');
            $table->index('id_vaga', 'idx_candidatura_vaga');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatura');
    }
};
