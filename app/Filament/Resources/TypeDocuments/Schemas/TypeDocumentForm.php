<?php

namespace App\Filament\Resources\TypeDocuments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TypeDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                Toggle::make('status')
                    ->required(),
                TextInput::make('response_time_days')
                    ->required()
                    ->numeric(),
            ]);
    }
}
