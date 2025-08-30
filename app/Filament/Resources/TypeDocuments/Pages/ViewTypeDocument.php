<?php

namespace App\Filament\Resources\TypeDocuments\Pages;

use App\Filament\Resources\TypeDocuments\TypeDocumentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTypeDocument extends ViewRecord
{
    protected static string $resource = TypeDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
