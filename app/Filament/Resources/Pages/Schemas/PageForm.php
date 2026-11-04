<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Enums\PageStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Section::make(__('Konten Halaman'))
                        ->columnSpan(2)
                        ->schema([
                            TextInput::make('title')
                                ->label(__('Judul Halaman'))
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                            TextInput::make('slug')
                                ->label(__('Slug URL'))
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                            RichEditor::make('content')
                                ->label(__('Isi Konten'))
                                ->required()
                                ->fileAttachmentsDisk('public')
                                ->fileAttachmentsDirectory('pages/attachments')
                                ->fileAttachmentsVisibility('public')
                                ->columnSpanFull(),
                        ]),
                    Section::make(__('Pengaturan'))
                        ->columnSpan(1)
                        ->schema([
                            FileUpload::make('image')
                                ->label(__('Gambar Sampul (Header Banner)'))
                                ->image()
                                ->disk('public')
                                ->directory('pages')
                                ->visibility('public'),
                            Select::make('status')
                                ->label(__('Status'))
                                ->options(PageStatus::class)
                                ->default(PageStatus::Published)
                                ->required(),
                            TextInput::make('sort_order')
                                ->label(__('Urutan'))
                                ->numeric()
                                ->default(0),
                            Toggle::make('show_in_menu')
                                ->label(__('Tampilkan di Pilihan Menu Navigasi'))
                                ->default(true),
                        ]),
                ]),
            ]);
    }
}
