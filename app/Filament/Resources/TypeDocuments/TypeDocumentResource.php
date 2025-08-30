<?php

namespace App\Filament\Resources\TypeDocuments;

use App\Filament\Resources\TypeDocuments\Pages\CreateTypeDocument;
use App\Filament\Resources\TypeDocuments\Pages\EditTypeDocument;
use App\Filament\Resources\TypeDocuments\Pages\ListTypeDocuments;
use App\Filament\Resources\TypeDocuments\Pages\ViewTypeDocument;
use App\Filament\Resources\TypeDocuments\Schemas\TypeDocumentForm;
use App\Filament\Resources\TypeDocuments\Schemas\TypeDocumentInfolist;
use App\Filament\Resources\TypeDocuments\Tables\TypeDocumentsTable;
use App\Models\TypeDocument;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TypeDocumentResource extends Resource
{
    protected static ?string $model = TypeDocument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'TypeDocument';

    public static function form(Schema $schema): Schema
    {
        return TypeDocumentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TypeDocumentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TypeDocumentsTable::configure($table);
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
            'index' => ListTypeDocuments::route('/'),
            'create' => CreateTypeDocument::route('/create'),
            'view' => ViewTypeDocument::route('/{record}'),
            'edit' => EditTypeDocument::route('/{record}/edit'),
        ];
    }
}
