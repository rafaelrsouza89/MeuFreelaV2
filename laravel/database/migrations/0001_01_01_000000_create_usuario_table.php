<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->string('email', 100)->unique();
            $table->string('senha', 255);
            $table->rememberToken();
            $table->string('telefone', 20)->nullable();
            $table->enum('tipo_usuario', ['freelancer', 'contratante', 'ambos']);
            $table->dateTime('data_cadastro');
            $table->text('biografia')->nullable();
            $table->string('especialidades', 255)->nullable();
            $table->string('portfolio_url', 255)->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('cpf', 20)->nullable();
            $table->string('linkedin', 255)->nullable();
            $table->string('cep', 10)->nullable();
            $table->string('estado', 50)->nullable();
            $table->string('cidade', 100)->nullable();
            $table->string('bairro', 100)->nullable();
            $table->string('logradouro', 255)->nullable();
            $table->string('numero', 20)->nullable();
            $table->string('foto_perfil', 500)->nullable();
            $table->string('reset_token_hash', 255)->nullable();
            $table->dateTime('reset_token_expires_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
