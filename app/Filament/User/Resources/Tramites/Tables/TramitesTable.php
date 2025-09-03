<?php

namespace App\Filament\User\Resources\Tramites\Tables;

use App\Models\Area;
use App\Models\Derivation;
use App\Models\Tramite;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;

class TramitesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->searchable(),

                TextColumn::make('full_name')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),

                TextColumn::make('documentTypes.name')
                    ->badge()
                    ->sortable(),
                TextColumn::make('areaOrigen.name')
                    ->label('Area de Origen')
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('folio')
                    ->searchable(),
                TextColumn::make('reception_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Borrador',
                        'received' => 'Recibido',
                        'in_process' => 'En Proceso',
                        'derived' => 'Derivado',
                        'returned' => 'Devuelto',
                        'archived' => 'Archivado',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
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
                Action::make('derivar')
                    ->label('Derivar')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->fillForm(fn (Tramite $record) => [
                        'tramite_id' => $record->id,
                        'from_area_id' => $record->area_oreigen_id,
                        'user_id' => auth()->id(),
                        'received_at' => now(),
                        'file_path' => $record->file_path,
                    ])
                    ->form([
                        Select::make('tramite_id')
                            ->label('Tramite')
                            ->options(Tramite::all()->pluck('number', 'id'))
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Select::make('from_area_id')
                            ->label('Área Origen')
                            ->options(Area::all()->pluck('name', 'id'))
                            ->disabled()
                            ->dehydrated()
                            ->nullable(),
                        Select::make('to_area_id')
                            ->label('Área destino')
                            ->options(Area::pluck('name', 'id'))
                            ->required(),
                        Select::make('user_id')
                            ->label('Usuario')
                            ->options(User::all()->pluck('name', 'id'))
                            ->default(auth()->id())
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        DateTimePicker::make('received_at')
                            ->label('Fecha de Recepción')
                            // ->default(now())
                            // ->minDate(now())
                            // ->disabled()
                            // ->dehydrated()
                            ->required()
                            ->displayFormat('d/m/Y H:i'),
                        Select::make('status')
                            ->label('Estado de la Derivación')
                            ->options([
                                'sent' => 'Enviado',
                                'received' => 'Recibido',
                                'returned' => 'Devuelto',
                            ])
                            ->default('sent')
                            ->native(false)
                            ->required(),
                        Textarea::make('observations')
                            ->label('Observaciones')
                            ->maxLength(500),
                    ])
                    ->action(function (Tramite $record, array $data): void {
                        // Crear la derivación directamente
                        $derivation = new Derivation;
                        $derivation->tramite_id = $record->id;
                        $derivation->from_area_id = $record->area_oreigen_id;
                        $derivation->to_area_id = $data['to_area_id'];
                        $derivation->user_id = auth()->id();
                        $derivation->observations = $data['observations'] ?? null;
                        $derivation->status = $data['status'] ?? 'sent';
                        $derivation->received_at = $data['received_at'] ?? now();
                        $derivation->save();

                        // Actualizar estado del trámite
                        // $newStatus = match ($data['status']) {
                        //     'sent' => 'derived',
                        //     'received' => 'in_process',
                        //     'returned' => 'returned',
                        //     default => $record->status,
                        // };
                        // $record->update([
                        //     'status' => $newStatus,
                        //     'area_oreigen_id' => $data['to_area_id'],
                        // ]);
                        $record->actualizarEstadoDesdeDerivacion(
                            $data['status'],
                            $data['to_area_id']
                        );

                        // Notificación de éxito
                        \Filament\Notifications\Notification::make()
                            ->title('Derivación creada exitosamente')
                            ->body("Trámite derivado al área destino. ID: {$derivation->id}")
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalIcon('gmdi-move-up-r')
                    ->modalHeading('Derivar trámite')
                    ->modalSubmitActionLabel('Derivar ahora')
                    ->modalWidth(Width::SevenExtraLarge)
                    ->color('info'),
                MediaAction::make('pdf')
                    ->label('Ver PDF')
                    ->color('danger')
                    // ->button()
                    ->media(fn ($record) => $record->file_path ? asset('storage/'.$record->file_path) : null)
                    ->icon('bi-file-pdf-fill')
                    ->visible(fn ($record) => ! empty($record->file_path)),
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
