<?php

namespace App\Filament\User\Resources\Tramites\Tables;

use App\Models\Tramite;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class TramitesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Tramite::where('area_oreigen_id', Auth::user()->area_id)
            )
            ->striped()
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('number')
                    ->label('Codigo')
                    ->searchable(),
                TextColumn::make('full_name')
                    ->label('Nombre')
                    ->searchable(),

                TextColumn::make('document_type_id')
                    ->label('tipo')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('area_oreigen_id')
                    ->label('Origen')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user_id')
                    ->label('Usuario')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('folio')
                    ->label('folio')
                    ->searchable(),
                TextColumn::make('reception_date')
                    ->label('Fecha Recepcion')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
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

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'draft' => 'Borrador',
                        'received' => 'Recibido',
                        'in_process' => 'En Proceso',
                        'derived' => 'Derivado',
                        'returned' => 'Devuelto',
                        'archived' => 'Archivado',
                    ]),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
