<?php

namespace App\Filament\Resources\Recebimentos;

use App\Filament\Resources\Recebimentos\Pages\CreateRecebimento;
use App\Filament\Resources\Recebimentos\Pages\EditRecebimento;
use App\Filament\Resources\Recebimentos\Pages\ListRecebimentos;
use App\Filament\Resources\Recebimentos\Pages\ViewRecebimento;
use App\Filament\Resources\Recebimentos\Schemas\RecebimentoForm;
use App\Filament\Resources\Recebimentos\Schemas\RecebimentoInfolist;
use App\Filament\Resources\Recebimentos\Tables\RecebimentosTable;
use App\Models\Recebimento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RecebimentoResource extends Resource
{
    protected static ?string $model = Recebimento::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-truck';
    
    protected static \UnitEnum|string|null $navigationGroup = 'Expedição & Recebimento';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return RecebimentoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RecebimentoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecebimentosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Recebimentos\RelationManagers\LotesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRecebimentos::route('/'),
            'create' => CreateRecebimento::route('/create'),
            'view' => ViewRecebimento::route('/{record}'),
            'edit' => EditRecebimento::route('/{record}/edit'),
        ];
    }
}
