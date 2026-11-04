<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Kelola Tanya Jawab (FAQ)'))
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('topic_id')
                                ->label(__('Topik Kategori'))
                                ->relationship('topic', 'name')
                                ->searchable()
                                ->preload()
                                ->columnSpan(1),
                            TextInput::make('sort_order')
                                ->label(__('Urutan Tampil'))
                                ->numeric()
                                ->default(0)
                                ->columnSpan(1),
                            Toggle::make('is_active')
                                ->label(__('Aktif / Tampilkan'))
                                ->default(true)
                                ->inline(false)
                                ->columnSpan(1),
                        ]),
                        TextInput::make('question')
                            ->label(__('Pertanyaan yang Sering Diajukan'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        RichEditor::make('answer')
                            ->label(__('Jawaban Lengkap'))
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
