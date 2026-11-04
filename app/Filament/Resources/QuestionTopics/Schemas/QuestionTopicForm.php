<?php

namespace App\Filament\Resources\QuestionTopics\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class QuestionTopicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Informasi Topik Pertanyaan'))
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label(__('Nama Topik'))
                                ->placeholder(__('Contoh: Administrasi Umum, Pelayanan Publik'))
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                            TextInput::make('slug')
                                ->label(__('Slug URL'))
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                        ]),
                        Textarea::make('description')
                            ->label(__('Keterangan Topik'))
                            ->rows(3)
                            ->columnSpanFull(),
                        Grid::make(3)->schema([
                            TextInput::make('icon')
                                ->label(__('Ikon'))
                                ->maxLength(255),
                            TextInput::make('sort_order')
                                ->label(__('Urutan'))
                                ->numeric()
                                ->default(0),
                            Toggle::make('is_active')
                                ->label(__('Aktif'))
                                ->default(true)
                                ->inline(false),
                        ]),
                    ]),
            ]);
    }
}
