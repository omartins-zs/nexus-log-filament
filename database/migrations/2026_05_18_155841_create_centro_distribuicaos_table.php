<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('centro_distribuicoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('codigo_interno')->unique();
            $table->string('cidade');
            $table->string('estado');
            $table->string('endereco');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('centro_distribuicoes');
    }
};
