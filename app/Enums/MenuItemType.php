<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MenuItemType: string implements HasLabel
{
    case Custom = 'custom';
    case Page = 'page';
    case Post = 'post';
    case Category = 'category';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Custom => 'Kustom URL',
            self::Page => 'Halaman Statis',
            self::Post => 'Artikel / Berita',
            self::Category => 'Kategori',
        };
    }
}

