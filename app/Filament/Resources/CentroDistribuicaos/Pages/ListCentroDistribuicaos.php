<?php

namespace App\Filament\Resources\CentroDistribuicaos\Pages;

use App\Filament\Resources\CentroDistribuicaos\CentroDistribuicaoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCentroDistribuicaos extends ListRecords
{
    protected static string $resource = CentroDistribuicaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
