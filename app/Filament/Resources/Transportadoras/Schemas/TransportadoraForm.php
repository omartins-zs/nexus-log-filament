<?php

namespace App\Filament\Resources\Transportadoras\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class TransportadoraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dados da Transportadora')
                    ->description('Cadastre as transportadoras parceiras e suas taxas e prazos de entrega padrão')
                    ->components([
                        Grid::make(2)
                            ->components([
                                TextInput::make('nome')
                                    ->label('Nome Comercial')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ex: Alfa Transportes Express'),
                                TextInput::make('cnpj')
                                    ->label('CNPJ')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(18)
                                    ->mask('99.999.999/9999-99')
                                    ->placeholder('00.000.000/0000-00'),
                            ]),
                        Grid::make(2)
                            ->components([
                                TextInput::make('email')
                                    ->label('E-mail Comercial')
                                    ->email()
                                    ->maxLength(255)
                                    ->placeholder('Ex: comercial@alfa.com'),
                                TextInput::make('telefone')
                                    ->label('Telefone')
                                    ->tel()
                                    ->maxLength(20)
                                    ->mask('(99) 99999-9999')
                                    ->placeholder('(00) 00000-0000'),
                            ]),
                        Grid::make(3)
                            ->components([
                                TextInput::make('prazo_medio_entrega')
                                    ->label('Prazo Médio (Dias)')
                                    ->numeric()
                                    ->default(3)
                                    ->required(),
                                TextInput::make('valor_base_frete')
                                    ->label('Valor Base Frete (R$)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->default(0.00)
                                    ->required(),
                                Toggle::make('ativo')
                                    ->label('Status Ativo')
                                    ->default(true)
                                    ->inline(false),
                            ]),
                    ])
            ]);
    }
}
