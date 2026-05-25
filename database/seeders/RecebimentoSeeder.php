<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecebimentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produtos = \App\Models\Produto::all();
        $enderecos = \App\Models\Endereco::all();

        if ($produtos->isEmpty() || $enderecos->isEmpty()) {
            return;
        }

        // Criar 15 Recebimentos Concluídos
        for ($i = 1; $i <= 15; $i++) {
            $recebimento = \App\Models\Recebimento::create([
                'codigo_nfe' => 'NFE' . rand(100000, 999999),
                'fornecedor' => 'Fornecedor Master ' . chr(rand(65, 90)),
                'data_recebimento' => now()->subDays(rand(1, 30)),
                'status' => 'concluido',
                'observacoes' => 'Carga recebida perfeitamente.',
            ]);

            // Cada recebimento tem de 1 a 3 lotes
            $numLotes = rand(1, 3);
            for ($j = 0; $j < $numLotes; $j++) {
                $produto = $produtos->random();
                $endereco = $enderecos->random();
                $qtd = rand(10, 100);

                \App\Models\Lote::create([
                    'produto_id' => $produto->id,
                    'recebimento_id' => $recebimento->id,
                    'endereco_id' => $endereco->id,
                    'codigo_lote' => 'LOTE-' . strtoupper(uniqid()),
                    'data_fabricacao' => now()->subMonths(rand(1, 6)),
                    'data_validade' => now()->addMonths(rand(6, 24)),
                    'quantidade_inicial' => $qtd,
                    'quantidade_atual' => $qtd,
                ]);

                // Atualizar o estoque do produto gerando log
                $produto->quantidade_estoque += $qtd;
                $produto->save();
            }
        }
    }
}
