<?php

namespace App\Filament\Resources\Transportadoras\Pages;

use App\Filament\Resources\Transportadoras\TransportadoraResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTransportadora extends EditRecord
{
    protected static string $resource = TransportadoraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
