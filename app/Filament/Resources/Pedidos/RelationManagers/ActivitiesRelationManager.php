<?php

namespace App\Filament\Resources\Pedidos\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $recordTitleAttribute = 'description';

    protected static ?string $title = 'Histórico de Auditoria';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data / Hora')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                Tables\Columns\TextColumn::make('event')
                    ->label('Ação')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Usuário')
                    ->default('Sistema')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                \Filament\Actions\ViewAction::make()
                    ->modalHeading('Detalhes da Auditoria')
                    ->form([
                        Forms\Components\KeyValue::make('properties.old')
                            ->label('Valores Antigos')
                            ->visible(fn ($record) => $record->properties->has('old')),
                        Forms\Components\KeyValue::make('properties.attributes')
                            ->label('Valores Novos')
                            ->visible(fn ($record) => $record->properties->has('attributes')),
                    ]),
            ])
            ->bulkActions([
                //
            ]);
    }
}
