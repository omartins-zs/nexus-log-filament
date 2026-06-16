<?php

namespace Database\Seeders;

use App\Models\Embalagem;
use Illuminate\Database\Seeder;

class EmbalagemSeeder extends Seeder
{
    public function run(): void
    {
        $embalagens = [
            [
                'nome' => 'Lata 0.9L',
                'tipo' => 'lata',
                'capacidade_litros' => 0.90,
                'material' => 'metal',
                'ativo' => true,
            ],
            [
                'nome' => 'Lata 3.6L',
                'tipo' => 'lata',
                'capacidade_litros' => 3.60,
                'material' => 'metal',
                'ativo' => true,
            ],
            [
                'nome' => 'Galão 18L',
                'tipo' => 'galao',
                'capacidade_litros' => 18.00,
                'material' => 'plástico',
                'ativo' => true,
            ],
            [
                'nome' => 'Balde 20L',
                'tipo' => 'balde',
                'capacidade_litros' => 20.00,
                'material' => 'plástico',
                'ativo' => true,
            ],
            [
                'nome' => 'Tambor 200L',
                'tipo' => 'tambor',
                'capacidade_litros' => 200.00,
                'material' => 'metal',
                'ativo' => true,
            ],
            [
                'nome' => 'Frasco 500ml',
                'tipo' => 'frasco',
                'capacidade_litros' => 0.50,
                'material' => 'plástico',
                'ativo' => true,
            ],
            [
                'nome' => 'Sachê 50ml',
                'tipo' => 'sachê',
                'capacidade_litros' => 0.05,
                'material' => 'plástico',
                'ativo' => true,
            ],
        ];

        foreach ($embalagens as $embalagem) {
            Embalagem::updateOrCreate(
                ['nome' => $embalagem['nome']],
                $embalagem
            );
        }
    }
}
