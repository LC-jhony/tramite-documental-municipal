<?php

namespace App\Filament\User\Pages;

use App\Models\Derivation;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use App\Models\Tramite;
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\Auth;

class Document extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.user.pages.document';

    protected static string|BackedEnum|null $navigationIcon = "sui-document-stack";

    public function table(Table $table): Table
    {
        return $table
            ->query(Derivation::inbox(Auth::user()->area_id))
            ->groups([
                Group::make('tramite.number')
                    ->label('Documento')
                    ->collapsible()
            ])
            ->columns([
                TextColumn::make('tramite.number')
                    ->searchable(),
                TextColumn::make('toArea.name')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'draft' => 'Borrador',
                        'received' => 'Recibido',
                        'in_process' => 'En Proceso',
                        'derived' => 'Derivado',
                        'returned' => 'Devuelto',
                        'archived' => 'Archivado',
                        default => ucfirst($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'received' => 'info',
                        'in_process' => 'warning',
                        'derived' => 'primary',
                        'returned' => 'danger',
                        'archived' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('received_at')
                    ->label('Fecha de Recepcion')
                    ->date()

            ]);
    }
}
