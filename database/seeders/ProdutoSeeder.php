<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Produto;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        // Recuperar Clientes criados pelo ClienteSeeder por CNPJ
        $clienteTech = Cliente::where('cnpj', '12.345.678/0001-90')->first();
        $clienteModa = Cliente::where('cnpj', '98.765.432/0001-10')->first();
        $clienteHome = Cliente::where('cnpj', '45.678.123/0001-44')->first();

        if ($clienteTech) {
            Produto::updateOrCreate(
                ['sku' => 'TECH-EAR-ANC01'],
                [
                    'cliente_id' => $clienteTech->id,
                    'nome' => 'Fone Ouvido Bluetooth ANC',
                    'peso' => 0.25,
                    'altura' => 8.00,
                    'largura' => 15.00,
                    'comprimento' => 15.00,
                    'quantidade_estoque' => 120,
                ]
            );

            Produto::updateOrCreate(
                ['sku' => 'TECH-MOU-ERGO'],
                [
                    'cliente_id' => $clienteTech->id,
                    'nome' => 'Mouse Sem Fio Ergonomico',
                    'peso' => 0.15,
                    'altura' => 5.00,
                    'largura' => 8.00,
                    'comprimento' => 12.00,
                    'quantidade_estoque' => 4, // Alerta baixo estoque
                ]
            );

            Produto::updateOrCreate(
                ['sku' => 'TECH-KEY-RGB09'],
                [
                    'cliente_id' => $clienteTech->id,
                    'nome' => 'Teclado Mecanico RGB GPRO',
                    'peso' => 0.85,
                    'altura' => 4.00,
                    'largura' => 18.00,
                    'comprimento' => 45.00,
                    'quantidade_estoque' => 8, // Alerta baixo estoque
                ]
            );
        }

        if ($clienteModa) {
            Produto::updateOrCreate(
                ['sku' => 'MODA-TSH-PIMAP-G'],
                [
                    'cliente_id' => $clienteModa->id,
                    'nome' => 'Camiseta Algodao Pima Preta G',
                    'peso' => 0.20,
                    'altura' => 2.00,
                    'largura' => 25.00,
                    'comprimento' => 30.00,
                    'quantidade_estoque' => 350,
                ]
            );

            Produto::updateOrCreate(
                ['sku' => 'MODA-JNS-SLIM-42'],
                [
                    'cliente_id' => $clienteModa->id,
                    'nome' => 'Calca Jeans Slim Masculina 42',
                    'peso' => 0.60,
                    'altura' => 4.00,
                    'largura' => 30.00,
                    'comprimento' => 40.00,
                    'quantidade_estoque' => 75,
                ]
            );
        }

        if ($clienteHome) {
            Produto::updateOrCreate(
                ['sku' => 'HOME-CAF-ITA06'],
                [
                    'cliente_id' => $clienteHome->id,
                    'nome' => 'Cafeteira Espresso Italiana 6x',
                    'peso' => 1.20,
                    'altura' => 22.00,
                    'largura' => 14.00,
                    'comprimento' => 14.00,
                    'quantidade_estoque' => 45,
                ]
            );
        }
    }
}
