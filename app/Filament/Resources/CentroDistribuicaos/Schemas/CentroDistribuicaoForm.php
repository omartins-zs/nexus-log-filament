<?php

namespace App\Filament\Resources\CentroDistribuicaos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class CentroDistribuicaoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Centro de Distribuição')
                    ->description('Cadastre os galpões e CD locais')
                    ->components([
                        Grid::make(2)
                            ->components([
                                TextInput::make('nome')
                                    ->label('Nome do CD')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ex: CD Central São Paulo'),
                                TextInput::make('codigo_interno')
                                    ->label('Código Interno')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(50)
                                    ->placeholder('Ex: CD-SP-01'),
                            ]),
                        Grid::make(2)
                            ->components([
                                TextInput::make('cidade')
                                    ->label('Cidade')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('Ex: Guarulhos'),
                                TextInput::make('estado')
                                    ->label('Estado (UF)')
                                    ->required()
                                    ->maxLength(2)
                                    ->placeholder('Ex: SP'),
                            ]),
                        TextInput::make('endereco')
                            ->label('Endereço Completo')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Rodovia Dutra, Km 210'),
                    ])
            ]);
    }
}
