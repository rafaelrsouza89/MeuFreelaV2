<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vaga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuario')->onDelete('cascade');
            $table->string('titulo', 100);
            $table->text('descricao');
            $table->enum('tipo_vaga', ['remoto', 'presencial', 'hibrido']);
            $table->decimal('remuneracao', 10, 2)->nullable();
            $table->string('local', 255)->nullable();
            $table->dateTime('data_publicacao');
            $table->date('data_limite')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaga');
    }
};
