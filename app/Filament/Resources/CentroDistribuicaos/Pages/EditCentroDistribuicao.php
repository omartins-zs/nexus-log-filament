<?php

namespace App\Filament\Resources\CentroDistribuicaos\Pages;

use App\Filament\Resources\CentroDistribuicaos\CentroDistribuicaoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCentroDistribuicao extends EditRecord
{
    protected static string $resource = CentroDistribuicaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
