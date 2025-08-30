<?php

namespace App\Filament\User\Resources\Tramites\Pages;

use App\Filament\User\Resources\Tramites\TramiteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTramites extends ListRecords
{
    protected static string $resource = TramiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
