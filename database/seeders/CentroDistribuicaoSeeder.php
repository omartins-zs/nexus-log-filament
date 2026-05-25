<?php

namespace Database\Seeders;

use App\Models\CentroDistribuicao;
use Illuminate\Database\Seeder;

class CentroDistribuicaoSeeder extends Seeder
{
    public function run(): void
    {
        CentroDistribuicao::updateOrCreate(
            ['codigo_interno' => 'CD-GRU-01'],
            [
                'nome' => 'CD Dutra Guarulhos',
                'cidade' => 'Guarulhos',
                'estado' => 'SP',
                'endereco' => 'Rodovia Presidente Dutra, Km 212 - Jardim Cumbica',
            ]
        );

        CentroDistribuicao::updateOrCreate(
            ['codigo_interno' => 'CD-JOI-02'],
            [
                'nome' => 'CD Sul Joinville',
                'cidade' => 'Joinville',
                'estado' => 'SC',
                'endereco' => 'Rua Dona Francisca, 8000 - Distrito Industrial',
            ]
        );
    }
}
