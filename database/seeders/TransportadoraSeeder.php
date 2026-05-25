<?php

namespace Database\Seeders;

use App\Models\Transportadora;
use Illuminate\Database\Seeder;

class TransportadoraSeeder extends Seeder
{
    public function run(): void
    {
        Transportadora::updateOrCreate(
            ['cnpj' => '00.111.222/0001-33'],
            [
                'nome' => 'Total Express E-commerce',
                'email' => 'coleta@totalexpress.com.br',
                'telefone' => '(11) 3627-9000',
                'prazo_medio_entrega' => 3,
                'valor_base_frete' => 14.90,
                'ativo' => true,
            ]
        );

        Transportadora::updateOrCreate(
            ['cnpj' => '44.555.666/0001-77'],
            [
                'nome' => 'Jadlog Logística S/A',
                'email' => 'expedicao@jadlog.com.br',
                'telefone' => '(11) 3563-2000',
                'prazo_medio_entrega' => 5,
                'valor_base_frete' => 18.50,
                'ativo' => true,
            ]
        );

        Transportadora::updateOrCreate(
            ['cnpj' => '34.028.316/0001-03'],
            [
                'nome' => 'Correios - SEDEX',
                'email' => 'falecom@correios.com.br',
                'telefone' => '0800 725 0100',
                'prazo_medio_entrega' => 2,
                'valor_base_frete' => 24.00,
                'ativo' => true,
            ]
        );
    }
}
