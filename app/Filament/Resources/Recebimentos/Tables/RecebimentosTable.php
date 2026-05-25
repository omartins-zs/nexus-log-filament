<?php

namespace App\Filament\Resources\Recebimentos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RecebimentosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#ID')
                    ->sortable(),
                TextColumn::make('codigo_nfe')
                    ->label('Nota Fiscal')
                    ->searchable(),
                TextColumn::make('fornecedor')
                    ->searchable(),
                TextColumn::make('lotes_count')
                    ->counts('lotes')
                    ->label('Qtd. Lotes')
                    ->badge()
                    ->color('info'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'rascunho' => 'gray',
                        'concluido' => 'success',
                        'cancelado' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('data_recebimento')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
