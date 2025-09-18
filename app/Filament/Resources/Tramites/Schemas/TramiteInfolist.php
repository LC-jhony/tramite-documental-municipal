<?php

namespace App\Filament\Resources\Tramites\Schemas;

use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Joaopaulolndev\FilamentPdfViewer\Infolists\Components\PdfViewerEntry;

class TramiteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Trámite')
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('number'),
                        TextEntry::make('subject'),
                        TextEntry::make('origen'),
                        IconEntry::make('representation')
                            ->boolean(),
                        TextEntry::make('full_name'),
                        TextEntry::make('first_name'),
                        TextEntry::make('last_name'),
                        TextEntry::make('dni'),
                        TextEntry::make('phone'),
                        TextEntry::make('email')
                            ->label('Email address'),
                        TextEntry::make('address'),
                        TextEntry::make('ruc'),
                        TextEntry::make('empresa'),
                        TextEntry::make('document_type_id')
                            ->numeric(),
                        TextEntry::make('area_oreigen_id')
                            ->numeric(),
                        TextEntry::make('user_id')
                            ->numeric(),
                        TextEntry::make('folio'),
                        TextEntry::make('reception_date')
                            ->date(),
                        PdfViewerEntry::make('file_path')
                            ->label('View the PDF')
                            ->minHeight('40svh')
                            ->columnSpanFull(),
                        // ->fileUrl(fn($record) => $record->file_path ? asset('storage/' . $record->file_path) : null),
                        TextEntry::make('condition'),
                        TextEntry::make('status')
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
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ])
                    ->columns(3),

                Section::make('Historial de Derivaciones')
                    ->schema([
                        RepeatableEntry::make('derivaciones')
                            ->schema([
                                TextEntry::make('areaOrigen.name')
                                    ->label('De'),
                                TextEntry::make('areaDestino.name')
                                    ->label('Para'),
                                TextEntry::make('usuario.name')
                                    ->label('Derivado por'),
                                TextEntry::make('fecha_derivacion')
                                    ->label('Fecha')
                                    ->dateTime(),
                                TextEntry::make('estado')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'pendiente' => 'warning',
                                        'recibido' => 'info',
                                        'procesado' => 'primary',
                                        'respondido' => 'success',
                                        default => 'gray',
                                    }),
                                TextEntry::make('motivo')
                                    ->columnSpanFull(),
                            ])
                            ->columns(4)
                            ->placeholder('No hay derivaciones registradas'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Historial de Respuestas')
                    ->schema([
                        RepeatableEntry::make('respuestas')
                            ->schema([
                                TextEntry::make('area.name')
                                    ->label('Área'),
                                TextEntry::make('usuario.name')
                                    ->label('Respondido por'),
                                TextEntry::make('tipo_respuesta')
                                    ->label('Tipo')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'informativa' => 'info',
                                        'resolutiva' => 'success',
                                        'derivacion_interna' => 'warning',
                                        'solicitud_informacion' => 'primary',
                                        default => 'gray',
                                    }),
                                TextEntry::make('fecha_respuesta')
                                    ->label('Fecha')
                                    ->dateTime(),
                                TextEntry::make('contenido')
                                    ->columnSpanFull()
                                    ->prose(),
                                TextEntry::make('archivo_adjunto')
                                    ->label('Archivo Adjunto')
                                    ->url(fn($record) => $record->archivo_adjunto ? asset('storage/' . $record->archivo_adjunto) : null)
                                    ->openUrlInNewTab()
                                    ->placeholder('Sin archivo adjunto')
                                    ->visible(fn($record) => ! empty($record->archivo_adjunto)),
                            ])
                            ->columns(3)
                            ->placeholder('No hay respuestas registradas'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
