<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Upload Media'))
                    ->schema([
                        FileUpload::make('file_path')
                            ->label(__('Unggah Berkas / Gambar'))
                            ->disk('public')
                            ->directory('media')
                            ->visibility('public')
                            ->required()
                            ->preserveFilenames()
                            ->columnSpanFull(),
                        TextInput::make('file_name')
                            ->label(__('Nama Berkas'))
                            ->placeholder(__('Nama berkas asli'))
                            ->maxLength(255),
                        TextInput::make('alt_text')
                            ->label(__('Deskripsi / Alt Text'))
                            ->maxLength(255),
                        Select::make('user_id')
                            ->label(__('Pengunggah'))
                            ->relationship('user', 'name')
                            ->default(fn () => auth()->id()),
                    ]),
            ]);
    }
}
