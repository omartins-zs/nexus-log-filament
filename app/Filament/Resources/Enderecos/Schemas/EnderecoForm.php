<?php

namespace App\Filament\Resources\Enderecos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EnderecoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('corredor')
                    ->required(),
                TextInput::make('estante')
                    ->required(),
                TextInput::make('nivel')
                    ->required(),
                TextInput::make('codigo_barras')
                    ->required(),
            ]);
    }
}
