<?php

namespace App\Filament\Resources\Transportadoras;

use App\Filament\Resources\Transportadoras\Pages\CreateTransportadora;
use App\Filament\Resources\Transportadoras\Pages\EditTransportadora;
use App\Filament\Resources\Transportadoras\Pages\ListTransportadoras;
use App\Filament\Resources\Transportadoras\Schemas\TransportadoraForm;
use App\Filament\Resources\Transportadoras\Tables\TransportadorasTable;
use App\Models\Transportadora;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransportadoraResource extends Resource
{
    protected static ?string $model = Transportadora::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TransportadoraForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransportadorasTable::configure($table);
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
            'index' => ListTransportadoras::route('/'),
            'create' => CreateTransportadora::route('/create'),
            'edit' => EditTransportadora::route('/{record}/edit'),
        ];
    }
}
