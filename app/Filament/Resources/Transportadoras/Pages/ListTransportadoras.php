<?php

namespace App\Filament\Resources\Transportadoras\Pages;

use App\Filament\Resources\Transportadoras\TransportadoraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransportadoras extends ListRecords
{
    protected static string $resource = TransportadoraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
