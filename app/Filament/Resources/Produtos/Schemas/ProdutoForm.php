<?php

namespace App\Filament\Resources\Produtos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class ProdutoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações do Produto')
                    ->description('Insira os dados cadastrais básicos e o cliente proprietário')
                    ->components([
                        Grid::make(3)
                            ->components([
                                Select::make('cliente_id')
                                    ->label('Cliente Proprietário')
                                    ->relationship('cliente', 'nome')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('nome')
                                    ->label('Nome do Produto')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ex: Smartphone Galaxy S24'),
                                TextInput::make('sku')
                                    ->label('SKU')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(100)
                                    ->placeholder('Ex: CEL-GAL-S24'),
                            ]),
                    ]),

                Section::make('Especificações Físicas & Estoque')
                    ->description('Informe as dimensões para cálculo logístico de cubagem e estoque atual')
                    ->components([
                        Grid::make(4)
                            ->components([
                                TextInput::make('peso')
                                    ->label('Peso (Kg)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('0.00'),
                                TextInput::make('altura')
                                    ->label('Altura (cm)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('0.00'),
                                TextInput::make('largura')
                                    ->label('Largura (cm)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('0.00'),
                                TextInput::make('comprimento')
                                    ->label('Comprimento (cm)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('0.00'),
                            ]),
                        TextInput::make('quantidade_estoque')
                            ->label('Quantidade em Estoque')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->placeholder('Ex: 100'),
                    ]),
            ]);
    }
}
