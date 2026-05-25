<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->string('nome');
            $table->string('sku')->unique();
            $table->decimal('peso', 8, 2)->nullable();
            $table->decimal('altura', 8, 2)->nullable();
            $table->decimal('largura', 8, 2)->nullable();
            $table->decimal('comprimento', 8, 2)->nullable();
            $table->integer('quantidade_estoque')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
