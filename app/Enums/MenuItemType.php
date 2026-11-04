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
            self::Custom => __('Kustom URL'),
            self::Page => __('Halaman Statis'),
            self::Post => __('Artikel / Berita'),
            self::Category => __('Kategori'),
        };
    }
}
