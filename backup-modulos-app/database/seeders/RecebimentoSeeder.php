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

        // 1. Criar 10 Recebimentos Concluídos
        for ($i = 1; $i <= 10; $i++) {
            $recebimento = \App\Models\Recebimento::create([
                'codigo_nfe' => 'NFE' . rand(100000, 999999),
                'fornecedor' => 'Fornecedor Master ' . chr(64 + $i),
                'data_recebimento' => now()->subDays(rand(10, 30)),
                'status' => 'concluido',
                'observacoes' => 'Carga recebida e conferida.',
            ]);

            $numLotes = rand(1, 3);
            for ($j = 0; $j < $numLotes; $j++) {
                $produto = $produtos->random();
                $endereco = $enderecos->random();
                $qtd = rand(10, 100);

                \App\Models\Lote::create([
                    'produto_id' => $produto->id,
                    'recebimento_id' => $recebimento->id,
                    'endereco_id' => $endereco->id,
                    'codigo_lote' => 'LOTE-CONC-' . $i . '-' . $j,
                    'data_fabricacao' => now()->subMonths(rand(1, 6)),
                    'data_validade' => now()->addMonths(rand(6, 24)),
                    'quantidade_inicial' => $qtd,
                    'quantidade_atual' => $qtd,
                ]);

                $produto->increment('quantidade_estoque', $qtd);
            }
        }

        // 2. Criar 4 Recebimentos em status Rascunho (Pendente de Início)
        for ($i = 1; $i <= 4; $i++) {
            $recebimento = \App\Models\Recebimento::create([
                'codigo_nfe' => 'NFE' . rand(200000, 399999),
                'fornecedor' => 'Distribuidora Fenix ' . chr(68 + $i),
                'data_recebimento' => now()->subHours(rand(1, 12)),
                'status' => 'rascunho',
                'observacoes' => 'Aguardando conferência na doca.',
            ]);

            // Adicionar lotes específicos fáceis de digitar para testes
            $numLotes = rand(2, 3);
            for ($j = 0; $j < $numLotes; $j++) {
                $produto = $produtos->random();
                $endereco = $enderecos->random();
                $qtd = rand(10, 50);

                \App\Models\Lote::create([
                    'produto_id' => $produto->id,
                    'recebimento_id' => $recebimento->id,
                    'endereco_id' => $endereco->id,
                    'codigo_lote' => "LOTE-RASC-{$i}-{$j}",
                    'data_fabricacao' => now()->subDays(30),
                    'data_validade' => now()->addDays(365),
                    'quantidade_inicial' => $qtd,
                    'quantidade_atual' => $qtd,
                ]);
            }
        }

        // 3. Criar 3 Recebimentos em status Em Conferência (Em Progresso)
        for ($i = 1; $i <= 3; $i++) {
            $recebimento = \App\Models\Recebimento::create([
                'codigo_nfe' => 'NFE' . rand(400000, 599999),
                'fornecedor' => 'Metalúrgica Alfa ' . chr(75 + $i),
                'data_recebimento' => now()->subMinutes(rand(10, 120)),
                'status' => 'em_conferencia',
                'observacoes' => 'Conferência iniciada por operador.',
            ]);

            $numLotes = rand(2, 4);
            for ($j = 0; $j < $numLotes; $j++) {
                $produto = $produtos->random();
                $endereco = $enderecos->random();
                $qtd = rand(15, 60);

                \App\Models\Lote::create([
                    'produto_id' => $produto->id,
                    'recebimento_id' => $recebimento->id,
                    'endereco_id' => $endereco->id,
                    'codigo_lote' => "LOTE-CONF-{$i}-{$j}",
                    'data_fabricacao' => now()->subDays(15),
                    'data_validade' => now()->addDays(180),
                    'quantidade_inicial' => $qtd,
                    'quantidade_atual' => $qtd,
                ]);
            }
        }
    }
}
