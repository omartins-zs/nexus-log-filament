<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dados do Cliente')
                    ->description('Insira as informações cadastrais básicas do cliente')
                    ->components([
                        Grid::make(2)
                            ->components([
                                TextInput::make('nome')
                                    ->label('Nome do Cliente')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ex: Nexus Logística Ltda'),
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
                                    ->label('E-mail')
                                    ->email()
                                    ->maxLength(255)
                                    ->placeholder('Ex: contato@cliente.com'),
                                TextInput::make('telefone')
                                    ->label('Telefone')
                                    ->tel()
                                    ->maxLength(20)
                                    ->mask('(99) 99999-9999')
                                    ->placeholder('(00) 00000-0000'),
                            ]),
                        TextInput::make('endereco')
                            ->label('Endereço')
                            ->maxLength(255)
                            ->placeholder('Rua, Número, Bairro, Cidade - Estado'),
                    ])
            ]);
    }
}
