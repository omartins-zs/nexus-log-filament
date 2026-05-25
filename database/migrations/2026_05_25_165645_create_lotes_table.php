<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recebimento_id')->nullable()->constrained()->nullOnDelete();
            $table->string('codigo_lote');
            $table->date('data_fabricacao')->nullable();
            $table->date('data_validade')->nullable();
            $table->integer('quantidade_inicial')->default(0);
            $table->integer('quantidade_atual')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
