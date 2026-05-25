<?php

namespace App\Filament\Resources\Recebimentos\Pages;

use App\Filament\Resources\Recebimentos\RecebimentoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRecebimento extends ViewRecord
{
    protected static string $resource = RecebimentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
