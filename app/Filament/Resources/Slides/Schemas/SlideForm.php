<?php

namespace App\Filament\Resources\Slides\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Slide')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Judul Banner')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('link_url')
                                ->label('Tautan Tujuan (Link URL)')
                                ->placeholder('https://... atau /berita/...')
                                ->maxLength(255),
                        ]),
                        Textarea::make('description')
                            ->label('Keterangan / Sub-judul')
                            ->rows(2)
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Gambar Banner')
                            ->image()
                            ->disk('public')
                            ->directory('slides')
                            ->visibility('public')
                            ->required()
                            ->columnSpanFull(),
                        Grid::make(4)->schema([
                            DatePicker::make('start_date')
                                ->label('Mulai Tayang'),
                            DatePicker::make('end_date')
                                ->label('Selesai Tayang'),
                            TextInput::make('sort_order')
                                ->label('Urutan')
                                ->numeric()
                                ->default(0),
                            Toggle::make('is_active')
                                ->label('Aktif')
                                ->default(true)
                                ->inline(false),
                        ]),
                    ]),
            ]);
    }
}
