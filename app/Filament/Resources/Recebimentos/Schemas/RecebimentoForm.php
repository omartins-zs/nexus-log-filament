<?php

namespace App\Filament\Resources\Recebimentos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RecebimentoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Section::make('Dados da Entrada')
                    ->schema([
                        TextInput::make('codigo_nfe')
                            ->label('Nota Fiscal (Chave/Número)')
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('fornecedor')
                            ->label('Fornecedor')
                            ->maxLength(255)
                            ->required(),
                        DateTimePicker::make('data_recebimento')
                            ->label('Data de Recebimento')
                            ->default(now())
                            ->required(),
                        \Filament\Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'rascunho' => 'Rascunho (Em digitação)',
                                'concluido' => 'Concluído (Estoque Atualizado)',
                                'cancelado' => 'Cancelado',
                            ])
                            ->default('rascunho')
                            ->required(),
                        Textarea::make('observacoes')
                            ->label('Observações')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
