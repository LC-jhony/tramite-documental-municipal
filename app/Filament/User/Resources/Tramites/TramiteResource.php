<?php

namespace App\Filament\User\Resources\Tramites;

use App\Filament\User\Resources\Tramites\Pages\CreateTramite;
use App\Filament\User\Resources\Tramites\Pages\EditTramite;
use App\Filament\User\Resources\Tramites\Pages\ListTramites;
use App\Filament\User\Resources\Tramites\Pages\ViewTramite;
use App\Filament\User\Resources\Tramites\Schemas\TramiteForm;
use App\Filament\User\Resources\Tramites\Schemas\TramiteInfolist;
use App\Filament\User\Resources\Tramites\Tables\TramitesTable;
use App\Models\Tramite;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TramiteResource extends Resource
{
    protected static ?string $model = Tramite::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Tramite';

    public static function form(Schema $schema): Schema
    {
        return TramiteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TramiteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TramitesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTramites::route('/'),
            'create' => CreateTramite::route('/create'),
            'view' => ViewTramite::route('/{record}'),
            'edit' => EditTramite::route('/{record}/edit'),
        ];
    }
}
