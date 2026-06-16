<?php

namespace App\Filament\Resources\Embalagens;

use App\Filament\Resources\Embalagens\Pages\CreateEmbalagem;
use App\Filament\Resources\Embalagens\Pages\EditEmbalagem;
use App\Filament\Resources\Embalagens\Pages\ListEmbalagens;
use App\Models\Embalagem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class EmbalagemResource extends Resource
{
    protected static ?string $model = Embalagem::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cube';

    protected static \UnitEnum|string|null $navigationGroup = 'Cadastros';

    protected static ?string $modelLabel = 'Embalagem';

    protected static ?string $pluralModelLabel = 'Embalagens';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),

                Select::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'lata' => 'Lata',
                        'galao' => 'Galão',
                        'balde' => 'Balde',
                        'tambor' => 'Tambor',
                        'sachê' => 'Sachê',
                        'frasco' => 'Frasco',
                    ])
                    ->required(),

                TextInput::make('capacidade_litros')
                    ->label('Capacidade (Litros)')
                    ->numeric()
                    ->step(0.01),

                TextInput::make('codigo_barras')
                    ->label('Código de Barras')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Select::make('material')
                    ->label('Material')
                    ->options([
                        'metal' => 'Metal',
                        'plástico' => 'Plástico',
                        'vidro' => 'Vidro',
                    ]),

                Toggle::make('ativo')
                    ->label('Ativo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->sortable(),

                TextColumn::make('capacidade_litros')
                    ->label('Capacidade')
                    ->suffix(' L')
                    ->sortable(),

                TextColumn::make('material')
                    ->label('Material')
                    ->sortable(),

                IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'lata' => 'Lata',
                        'galao' => 'Galão',
                        'balde' => 'Balde',
                        'tambor' => 'Tambor',
                        'sachê' => 'Sachê',
                        'frasco' => 'Frasco',
                    ]),

                TernaryFilter::make('ativo')
                    ->label('Ativo'),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmbalagens::route('/'),
            'create' => CreateEmbalagem::route('/create'),
            'edit' => EditEmbalagem::route('/{record}/edit'),
        ];
    }
}
