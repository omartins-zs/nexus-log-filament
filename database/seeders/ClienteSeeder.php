<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        Cliente::updateOrCreate(
            ['cnpj' => '12.345.678/0001-90'],
            [
                'nome' => 'TechStore Brasil Ltda',
                'email' => 'logistica@techstore.com',
                'telefone' => '(11) 98888-7777',
                'endereco' => 'Av. Paulista, 1000 - Bela Vista, São Paulo - SP',
            ]
        );

        Cliente::updateOrCreate(
            ['cnpj' => '98.765.432/0001-10'],
            [
                'nome' => 'Moda Fashion Outlet',
                'email' => 'expedicao@modafashion.com',
                'telefone' => '(21) 97777-6666',
                'endereco' => 'Av. das Américas, 5000 - Barra da Tijuca, Rio de Janeiro - RJ',
            ]
        );

        Cliente::updateOrCreate(
            ['cnpj' => '45.678.123/0001-44'],
            [
                'nome' => 'Home & Deco Utilidades',
                'email' => 'adm@homedeco.com',
                'telefone' => '(47) 3444-2222',
                'endereco' => 'Rua XV de Novembro, 400 - Centro, Blumenau - SC',
            ]
        );
    }
}
