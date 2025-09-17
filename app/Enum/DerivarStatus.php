<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum DerivarStatus: string implements HasLabel
{
    case Sent = 'sent';
    case Received = 'received';
    case Returned = 'returned';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Sent => 'Enviado',
            self::Received => 'Recibido',
            self::Returned => 'Devuelto',
        };
    }
}
