<?php

namespace App\Filament\Resources\Menus\Schemas;

use App\Enums\MenuItemType;
use App\Enums\MenuLocation;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Informasi Menu'))
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label(__('Nama Menu'))
                                ->placeholder(__('Contoh: Menu Utama'))
                                ->required()
                                ->maxLength(255),
                            Select::make('location')
                                ->label(__('Lokasi Penempatan'))
                                ->options(MenuLocation::class)
                                ->required(),
                            Toggle::make('is_active')
                                ->label(__('Aktif'))
                                ->default(true)
                                ->inline(false),
                        ]),
                    ]),
                Section::make(__('Item Menu Navigasi'))
                    ->description(__('Kelola daftar tautan menu di bawah ini.'))
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                TextInput::make('label')
                                    ->label(__('Teks Label'))
                                    ->required()
                                    ->columnSpan(2),
                                Select::make('type')
                                    ->label(__('Tipe Tautan'))
                                    ->options(MenuItemType::class)
                                    ->default(MenuItemType::Custom)
                                    ->required()
                                    ->columnSpan(1),
                                TextInput::make('url')
                                    ->label(__('URL / Path'))
                                    ->placeholder(__('/tentang-kami atau https://...'))
                                    ->columnSpan(2),
                                TextInput::make('sort_order')
                                    ->label(__('Urutan'))
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),
                                Toggle::make('is_active')
                                    ->label(__('Aktif'))
                                    ->default(true)
                                    ->inline(false)
                                    ->columnSpan(1),
                            ])
                            ->columns(7)
                            ->orderColumn('sort_order')
                            ->defaultItems(1)
                            ->reorderable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
