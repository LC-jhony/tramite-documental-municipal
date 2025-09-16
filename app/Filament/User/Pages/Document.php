<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Page;
use BackedEnum;

class Document extends Page
{
    protected string $view = 'filament.user.pages.document';
    protected static string|BackedEnum|null $navigationIcon = "sui-document-stack";
}
