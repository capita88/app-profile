<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\PostStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Section::make('Konten Artikel')
                        ->columnSpan(2)
                        ->schema([
                            TextInput::make('title')
                                ->label('Judul Artikel')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                            TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                            Textarea::make('summary')
                                ->label('Ringkasan / Sinopsis')
                                ->rows(3)
                                ->placeholder('Ringkasan singkat untuk tampilan cuplikan'),
                            RichEditor::make('content')
                                ->label('Isi Konten')
                                ->required()
                                ->fileAttachmentsDisk('public')
                                ->fileAttachmentsDirectory('posts/attachments')
                                ->fileAttachmentsVisibility('public')
                                ->columnSpanFull(),
                        ]),
                    Section::make('Publikasi & Meta')
                        ->columnSpan(1)
                        ->schema([
                            Select::make('category_id')
                                ->label('Kategori')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('user_id')
                                ->label('Penulis')
                                ->relationship('user', 'name')
                                ->default(fn () => auth()->id())
                                ->required(),
                            FileUpload::make('image')
                                ->label('Gambar Utama')
                                ->image()
                                ->disk('public')
                                ->directory('posts')
                                ->visibility('public'),
                            Select::make('status')
                                ->label('Status Publikasi')
                                ->options(PostStatus::class)
                                ->default(PostStatus::Draft)
                                ->required(),
                            DateTimePicker::make('published_at')
                                ->label('Jadwal / Waktu Terbit')
                                ->default(now()),
                            Toggle::make('is_featured')
                                ->label('Berita Utama (Featured)')
                                ->default(false),
                        ]),
                ]),
            ]);
    }
}
