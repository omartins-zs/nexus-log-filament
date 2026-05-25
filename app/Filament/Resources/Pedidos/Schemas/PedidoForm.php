<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use App\Enums\PedidoStatus;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class PedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Associação do Pedido')
                    ->description('Vincule o pedido às entidades corretas')
                    ->components([
                        Grid::make(3)
                            ->components([
                                Select::make('cliente_id')
                                    ->label('Cliente')
                                    ->relationship('cliente', 'nome')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Select::make('centro_distribuicao_id')
                                    ->label('Centro de Distribuição')
                                    ->relationship('centroDistribuicao', 'nome')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Select::make('produto_id')
                                    ->label('Produto')
                                    ->relationship('produto', 'nome')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),

                Section::make('Detalhes Comerciais & Logísticos')
                    ->description('Informe as quantidades, valores, transportadoras e rastreamento')
                    ->components([
                        Grid::make(3)
                            ->components([
                                TextInput::make('quantidade')
                                    ->label('Quantidade')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->placeholder('Ex: 5'),
                                TextInput::make('valor_total')
                                    ->label('Valor Total (R$)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->required()
                                    ->placeholder('0.00'),
                                Select::make('status')
                                    ->label('Status do Pedido')
                                    ->options(PedidoStatus::class)
                                    ->required()
                                    ->default(PedidoStatus::PENDENTE->value),
                            ]),
                        Grid::make(3)
                            ->components([
                                Select::make('transportadora_id')
                                    ->label('Transportadora')
                                    ->relationship('transportadora', 'nome')
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('codigo_rastreio')
                                    ->label('Código de Rastreio')
                                    ->maxLength(100)
                                    ->placeholder('Ex: NX-123456789-BR'),
                                DateTimePicker::make('data_pedido')
                                    ->label('Data do Pedido')
                                    ->default(now())
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->components([
                                DateTimePicker::make('data_envio')
                                    ->label('Data de Envio'),
                            ]),
                    ]),
            ]);
    }
}
