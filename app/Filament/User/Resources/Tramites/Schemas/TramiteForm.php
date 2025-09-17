<?php

namespace App\Filament\User\Resources\Tramites\Schemas;

use App\Models\Area;
use App\Models\Gestion;
use App\Models\Tramite;
use App\Models\TypeDocument;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Field;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Asmit\FilamentUpload\Enums\PdfViewFit;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Filament\Forms\Components\FileUpload;

class TramiteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Registre el tramite interno')
                    ->icon('sui-document-stack')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()

                            ->schema([
                                Section::make('Datos personales')
                                    ->icon('mdi-book-information-variant')
                                    ->columns(2)
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
                                        TextInput::make('number')
                                            ->label('Codigo de Documento')
                                            ->default('COD-INT-' . random_int(100000, 999999))
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
                                            ->default('Internal')
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
                                            ->options(Area::where('status', true)->pluck('name', 'id'))
                                            ->default(fn() => Auth::user()->area_id ?? 1) // Fallback al área con ID 1
                                            ->disabled()
                                            ->dehydrated()
                                            ->selectablePlaceholder(false)
                                            ->required()
                                            ->native(false),
                                        Select::make('gestion_id')
                                            ->label('Gestion')
                                            ->options(Gestion::where('status', true)->pluck('name', 'id'))
                                            ->default(fn() => Gestion::where('status', true)->first()?->id)
                                            ->disabled()
                                            ->dehydrated()
                                            ->native(false),
                                        TextInput::make('folio')
                                            ->label('Folio')
                                            ->hint('Forgotten your password? Bad luck.')
                                            ->required()
                                            ->numeric(),
                                        TextInput::make('user_id')
                                            ->label('Usuario')
                                            ->default(fn() => Auth::id())
                                            //->hidden()
                                            ->dehydrated(),
                                        RichEditor::make('subject')
                                            ->label('Asunto del documento')
                                            ->required()
                                            ->columnSpan(2),

                                    ]),
                                Section::make('Documento')
                                    ->icon('bi-file-earmark-pdf')
                                    ->iconColor('danger')
                                    ->columns(4)
                                    ->schema([
                                        FileUpload::make('file_path')
                                            ->label('Archivo')
                                            ->required()
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->disk('public')
                                            ->directory('tramites')
                                            ->columnSpanFull()
                                    ]),
                                Checkbox::make('condition')
                                    ->label('Acepto que todo acto administrativo derivado del presente procedimiento se me
                                            notifique a mi correo electrónico (numeral 4 del artículo 20° del Texto Único
                                            Ordenado de la Ley N° 27444)')
                                    ->rule('required')
                                    ->default(true)
                                    ->columnSpanFull(),
                            ])

                    ])
            ]);
    }
}
