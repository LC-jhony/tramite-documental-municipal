<?php

namespace App\Filament\User\Resources\Tramites\Pages;

use App\Filament\User\Resources\Tramites\TramiteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTramite extends ViewRecord
{
    protected static string $resource = TramiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
