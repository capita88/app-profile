<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Konfigurasi Pengaturan'))
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('group')
                                ->label(__('Kelompok Pengaturan'))
                                ->options([
                                    'general' => __('Umum (General)'),
                                    'contact' => __('Kontak & Alamat'),
                                    'social_media' => __('Media Sosial'),
                                    'branding' => __('Branding & Identitas'),
                                ])
                                ->default('general')
                                ->required(),
                            TextInput::make('key')
                                ->label(__('Kunci (Key)'))
                                ->placeholder(__('site_name, contact_email, dll'))
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                            Select::make('type')
                                ->label(__('Tipe Nilai'))
                                ->options([
                                    'text' => __('Teks Singkat'),
                                    'textarea' => __('Teks Panjang'),
                                    'boolean' => __('Boolean (Ya/Tidak)'),
                                    'image' => __('URL Gambar'),
                                    'json' => 'JSON',
                                ])
                                ->default('text')
                                ->required(),
                        ]),
                        Textarea::make('value')
                            ->label(__('Nilai Pengaturan (Value)'))
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
