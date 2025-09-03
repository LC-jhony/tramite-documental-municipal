<?php

namespace App\Filament\User\Resources\Tramites\Schemas;

use App\Models\Area;
use App\Models\Tramite;
use App\Models\TypeDocument;
use App\Models\User;
use Asmit\FilamentUpload\Enums\PdfViewFit;
use Asmit\FilamentUpload\Forms\Components\AdvancedFileUpload;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TramiteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Fieldset::make('Datos Personales')
                            ->schema([
                                Select::make('user_id')
                                    ->label('Usuario')
                                    ->options(User::where('id', auth()->user()->id)->pluck('name', 'id'))
                                    ->default(auth()->user()->id)
                                    ->native(false)
                                    ->required()
                                    ->disabled()
                                    ->dehydrated(),
                            ]),
                        Fieldset::make('Datos Tramite')
                            ->schema([
                                TextInput::make('number')
                                    ->label('Codigo de Documento')
                                    ->default('COD-'.random_int(100000, 999999))
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
                                    ->default(auth()->user()->area_id)
                                    ->native(false)
                                    ->columnSpan(2),
                                TextInput::make('folio')
                                    ->label('Folio')
                                    ->required()
                                    ->numeric(),
                                Textarea::make('subject')
                                    ->label('Asunto del documento')
                                    ->required()
                                    ->columnSpan(2),
                            ]),
                    ])
                    ->columns(1),

                Section::make('Documento')
                    ->schema([
                        AdvancedFileUpload::make('file_path')
                            ->label('Adjuntar documento')
                            // ->visibility('public')
                            ->directory('documents')
                            ->helperText(str('El archivo  **de tramite** debe de subirlo para realizar el tramite.')->inlineMarkdown()->toHtmlString())
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                    ->prepend('tramite-'),
                            )
                            ->acceptedFileTypes(['application/pdf'])
                            ->required()
                            ->pdfPreviewHeight(600) // Customize preview height
                            ->pdfDisplayPage(1) // Set default page
                            ->pdfToolbar(true) // Enable toolbar
                            ->pdfZoomLevel(100) // Set zoom level
                            ->pdfFitType(PdfViewFit::FIT) // Set fit type
                            ->pdfNavPanes(true), // Enable navigation panes
                    ])
                    ->collapsible(),
                Section::make()
                    ->schema([
                        Checkbox::make('condition')
                            ->label('Acepto que todo acto administrativo derivado del presente procedimiento se me
                                            notifique a mi correo electrónico (numeral 4 del artículo 20° del Texto Único
                                            Ordenado de la Ley N° 27444)')
                            ->rule('required')
                            ->default(true)
                            ->disabled()
                            ->dehydrated(),
                    ])->columnSpanFull(),
            ]);
    }
}
