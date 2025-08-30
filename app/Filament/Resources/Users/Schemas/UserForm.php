<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Area;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('area_id')
                    ->options(Area::where('status', true)->pluck('name', 'id'))
                    ->searchable()
                    ->native(false),
            ]);
    }
}
