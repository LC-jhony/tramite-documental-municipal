<?php

namespace App\Filament\Resources\Tramites\Schemas;

use App\Models\Tramite;
use App\Models\TypeDocument;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Fieldset;
use Asmit\FilamentUpload\Enums\PdfViewFit;
use Filament\Forms\Components\ToggleButtons;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Asmit\FilamentUpload\Forms\Components\AdvancedFileUpload;

class TramiteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Registre tramite')
                    ->description(' registre su tramite llene los campos requeridos para registrar su tramite')
                    ->schema([
                        Section::make('Datos Personales')
                            ->schema([
                                ToggleButtons::make('representation')
                                    ->label('Representante')
                                    ->boolean(
                                        trueLabel: 'Persona Natura',
                                        falseLabel: 'Persona Juridica'
                                    )
                                    ->icons([
                                        'heroicon-o-briefcase',
                                        'heroicon-o-user',
                                    ])
                                    ->live()
                                    ->grouped()
                                    ->default(true),
                                Fieldset::make('Persona Natural')
                                    ->visible(fn($get) => $get('representation') === true)
                                    ->schema([
                                        TextInput::make('dni')
                                            ->label('DNI')
                                            ->numeric()
                                            ->required()
                                            ->requiredWith('representation'),
                                        TextInput::make('full_name')
                                            ->label('Nombre')
                                            ->required()
                                            ->requiredWith('representation'),
                                        TextInput::make('last_name')
                                            ->label('Apellido paterno')
                                            ->required()
                                            ->requiredWith('representation'),
                                        TextInput::make('first_name')
                                            ->label('Apellido materno')
                                            ->required()
                                            ->requiredWith('representation'),
                                    ]),
                                Fieldset::make('Persona Juridica')
                                    ->visible(fn($get) => $get('representation') === false)
                                    ->schema([
                                        TextInput::make('ruc')
                                            ->numeric()
                                            ->requiredWith('representation'),
                                        TextInput::make('empresa')
                                            ->requiredWith('representation'),
                                    ]),
                                Grid::make()
                                    ->schema([
                                        PhoneInput::make('phone')
                                            ->label('Celular | Telefono')
                                            ->defaultCountry('PE')
                                            ->required()
                                            ->validationAttribute('telefono'),
                                        TextInput::make('email')
                                            ->label('Correo electronico')
                                            ->email()
                                            ->required()
                                            ->unique(Tramite::class, 'email', ignoreRecord: true),
                                        TextInput::make('address')
                                            ->label('direccion')
                                            ->columnSpan(2),
                                    ])->columns(2),

                            ])->columnSpan(2),
                        Section::make('Datos del Tramite')
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        TextInput::make('number')
                                            ->label('Codigo de Documento')
                                            ->default('COD-' . random_int(100000, 999999))
                                            ->disabled()
                                            ->dehydrated()
                                            ->required()
                                            ->maxLength(32)
                                            ->unique(Tramite::class, 'number', ignoreRecord: true),
                                        Select::make('document_type_id')
                                            ->label('Tipo documento')
                                            ->options(TypeDocument::where('status', true)->pluck('name', 'id'))
                                            ->searchable()
                                            ->required()
                                            ->native(false),
                                        Select::make('origen')
                                            ->options([
                                                'Internal' => 'Interno',
                                                'External' => 'Externo',
                                            ])
                                            ->default('External')
                                            ->disabled()
                                            ->dehydrated(),
                                        DatePicker::make('reception_date')
                                            ->label('Fecha de registro')
                                            ->default(now()->format('Y-m-d'))
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),
                                        Select::make('area_oreigen_id')
                                            ->label('Area')
                                            ->options([
                                                '1' => 'MESA DE PARTES',
                                            ])
                                            ->default('1')
                                            ->selectablePlaceholder(false)
                                            ->live()
                                            ->required()
                                            ->native(false),
                                        // Select::make('gestion_id')
                                        //     ->label('Gestion')
                                        //     ->options(Gestion::where('active', true)->pluck('name', 'id'))
                                        //     ->native(false),
                                        TextInput::make('folio')
                                            ->label('Folio')
                                            // ->hint('Forgotten your password? Bad luck.')
                                            ->required()

                                            ->numeric(),
                                    ])->columns(2),
                                Textarea::make('subject')
                                    ->label('Asunto del documento')
                                    ->required()
                                    ->columnSpan(2),
                            ])->columnSpan(2),
                        Checkbox::make('condition')
                            ->label('Acepto que todo acto administrativo derivado del presente procedimiento se me
                                            notifique a mi correo electrónico (numeral 4 del artículo 20° del Texto Único
                                            Ordenado de la Ley N° 27444)')
                            ->rule('required')

                            ->columnSpanFull(),
                    ])->columns(4),
                AdvancedFileUpload::make('file_path')
                    ->label('Adjuntar documento')
                    ->required()
                    ->pdfPreviewHeight(500) // Customize preview height
                    ->pdfDisplayPage(1) // Set default page
                    ->pdfToolbar(true) // Enable toolbar
                    ->pdfZoomLevel(100) // Set zoom level
                    ->pdfFitType(PdfViewFit::FIT) // Set fit type
                    ->pdfNavPanes(true) // Enable navigation panes

            ]);
    }
}
