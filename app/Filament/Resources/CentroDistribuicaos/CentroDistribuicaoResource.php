<?php

namespace App\Filament\Resources\CentroDistribuicaos;

use App\Filament\Resources\CentroDistribuicaos\Pages\CreateCentroDistribuicao;
use App\Filament\Resources\CentroDistribuicaos\Pages\EditCentroDistribuicao;
use App\Filament\Resources\CentroDistribuicaos\Pages\ListCentroDistribuicaos;
use App\Filament\Resources\CentroDistribuicaos\Schemas\CentroDistribuicaoForm;
use App\Filament\Resources\CentroDistribuicaos\Tables\CentroDistribuicaosTable;
use App\Models\CentroDistribuicao;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CentroDistribuicaoResource extends Resource
{
    protected static ?string $model = CentroDistribuicao::class;

    protected static ?string $modelLabel = 'Centro de Distribuição';
    
    protected static ?string $pluralModelLabel = 'Centros de Distribuição';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CentroDistribuicaoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CentroDistribuicaosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCentroDistribuicaos::route('/'),
            'create' => CreateCentroDistribuicao::route('/create'),
            'edit' => EditCentroDistribuicao::route('/{record}/edit'),
        ];
    }
}
