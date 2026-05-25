<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnderecoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $corredores = ['A', 'B', 'C', 'D'];
        
        foreach ($corredores as $corredor) {
            for ($estante = 1; $estante <= 5; $estante++) {
                for ($nivel = 1; $nivel <= 3; $nivel++) {
                    $estStr = str_pad($estante, 2, '0', STR_PAD_LEFT);
                    $nivStr = str_pad($nivel, 2, '0', STR_PAD_LEFT);
                    
                    \App\Models\Endereco::create([
                        'corredor' => $corredor,
                        'estante' => $estStr,
                        'nivel' => $nivStr,
                        'codigo_barras' => "{$corredor}-{$estStr}-{$nivStr}",
                    ]);
                }
            }
        }
    }
}
