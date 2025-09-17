<?php

namespace App\Filament\User\Resources\Tramites\Tables;

use App\Models\Area;
use App\Models\Tramite;
use Filament\Tables\Table;
use App\Enum\DerivarStatus;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;

class TramitesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Tramite::where(function ($query) {
                    $query->where('user_id', Auth::id())
                        ->orWhere('area_oreigen_id', Auth::user()->area_id);
                })
            )
            ->columns([
                TextColumn::make('number')
                    ->searchable(),
                TextColumn::make('origen')
                    ->alignCenter()
                    ->badge(),
                TextColumn::make('full_name')
                    ->searchable(),
                TextColumn::make('documentTypes.name')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('areaOrigen.name')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('folio')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('reception_date')
                    ->alignCenter()
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
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('Derivar')
                    //->icon('letsicon-folder-send-duotone')
                    //->iconSize('lg')
                    ->icon('iconsax-bol-send-2')
                    ->button()
                    ->slideOver()
                    ->fillForm(fn(Tramite $record) => [
                        'tramite_id' => $record->id,
                        'from_area_id' => $record->area_oreigen_id,
                        'user_id' => $record->user_id,
                        'file_path' => $record->file_path,
                    ])
                    ->form([
                        Section::make('Informacion del Documento')
                            ->columns(2)
                            ->schema([
                                Select::make('tramite_id')
                                    ->label('documento')
                                    ->options(Tramite::all()->pluck('number', 'id'))
                                    ->required()
                                    ->native(false),
                                Select::make('from_area_id')
                                    ->label('Area de Origen')
                                    ->options(Area::where('status', true)->pluck('name', 'id'))
                                    ->default(fn() => Auth::user()->area_id ?? 1) // Fallback al área con ID 1
                                    ->disabled()
                                    ->dehydrated(),

                            ]),
                        Section::make('Enviar documento')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('to_area_id')
                                            ->label('Area de Destino')
                                            ->options(Area::where('status', true)->pluck('name', 'id'))
                                            ->required()
                                            ->native(false),
                                        Select::make('user_id')
                                            ->label('Usuario')
                                            ->options([Auth::id() => Auth::user()->name])
                                            ->default(fn() => Auth::id())
                                            ->disabled()
                                            ->dehydrated(),
                                        Select::make('status')
                                            ->label('Estado')
                                            ->options(DerivarStatus::class)
                                            ->native(false),
                                        DatePicker::make('received_at')
                                            ->label('Fecha de Recepcion')
                                            ->default(now())
                                            ->native(false)

                                    ]),
                                RichEditor::make('observations')
                                    ->label('Observaciones')
                                    ->required()
                                    ->columnSpanFull()
                            ])
                    ])
                    ->action(function (array $data, Tramite $record) {
                        // Crear la derivación
                        $record->derivations()->create([
                            'tramite_id' => $data['tramite_id'],
                            'from_area_id' => $data['from_area_id'],
                            'to_area_id' => $data['to_area_id'],
                            'user_id' => $data['user_id'],
                            'observations' => $data['observations'],
                            'status' => $data['status'],
                            'received_at' => $data['received_at'],
                        ]);

                        // Actualizar el estado del trámite
                        $record->update([
                            'status' => 'derived',
                            'area_oreigen_id' => $data['to_area_id'],
                        ]);
                    }),
                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make(),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
