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
        Schema::table('lotes', function (Blueprint $table) {
            $table->foreignId('endereco_id')->nullable()->constrained('enderecos')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lotes', function (Blueprint $table) {
            $table->dropForeign(['endereco_id']);
            $table->dropColumn('endereco_id');
        });
    }
};
