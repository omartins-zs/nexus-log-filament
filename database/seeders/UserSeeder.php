<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@nexus.com'],
            [
                'name' => 'Gestor Nexus (Admin)',
                'password' => Hash::make('password'),
                'permissoes_mobile' => ['conferencia', 'inventario', 'separacao', 'transferencia', 'enderecamento'],
            ]
        );

        User::updateOrCreate(
            ['email' => 'conferente@nexus.com'],
            [
                'name' => 'Operador de Galpão',
                'password' => Hash::make('password'),
                'permissoes_mobile' => ['conferencia', 'inventario', 'separacao', 'transferencia', 'enderecamento'],
            ]
        );
    }
}
