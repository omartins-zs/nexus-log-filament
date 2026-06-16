<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->foreignId('embalagem_id')->nullable()->constrained('embalagens')->nullOnDelete();
            $table->string('cor')->nullable();
            $table->string('linha')->nullable();
            $table->string('tipo_tinta')->nullable();
            $table->string('codigo_barras')->unique()->nullable();
            $table->string('unidade_medida')->default('UN');
        });
    }

    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropForeign(['embalagem_id']);
            $table->dropColumn([
                'embalagem_id',
                'cor',
                'linha',
                'tipo_tinta',
                'codigo_barras',
                'unidade_medida',
            ]);
        });
    }
};
