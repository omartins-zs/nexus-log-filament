<?php

namespace App\Filament\Resources\Recebimentos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RecebimentoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('codigo_nfe')
                    ->placeholder('-'),
                TextEntry::make('fornecedor')
                    ->placeholder('-'),
                TextEntry::make('data_recebimento')
                    ->dateTime(),
                TextEntry::make('status'),
                TextEntry::make('observacoes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
