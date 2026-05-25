<?php

namespace App\Filament\Resources\Recebimentos\Pages;

use App\Filament\Resources\Recebimentos\RecebimentoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRecebimentos extends ListRecords
{
    protected static string $resource = RecebimentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
