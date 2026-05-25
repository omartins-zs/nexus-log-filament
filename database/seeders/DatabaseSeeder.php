<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ClienteSeeder::class,
            CentroDistribuicaoSeeder::class,
            ProdutoSeeder::class,
            EnderecoSeeder::class,
            RecebimentoSeeder::class,
            TransportadoraSeeder::class,
            PedidoSeeder::class,
        ]);
    }
}
