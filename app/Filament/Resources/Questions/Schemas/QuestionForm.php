<?php

namespace App\Filament\Resources\Questions\Schemas;

use App\Enums\QuestionStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Data Penanya & Topik'))
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label(__('Nama Lengkap'))
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label(__('Alamat Email'))
                                ->email()
                                ->required()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label(__('Nomor WhatsApp / HP'))
                                ->tel()
                                ->maxLength(255),
                        ]),
                        Grid::make(3)->schema([
                            Select::make('topic_id')
                                ->label(__('Topik Pertanyaan'))
                                ->relationship('topic', 'name')
                                ->searchable()
                                ->preload()
                                ->columnSpan(2),
                            Select::make('status')
                                ->label(__('Status Pertanyaan'))
                                ->options(QuestionStatus::class)
                                ->default(QuestionStatus::Pending)
                                ->required()
                                ->columnSpan(1),
                        ]),
                    ]),
                Section::make(__('Uraian Pertanyaan'))
                    ->schema([
                        TextInput::make('subject')
                            ->label(__('Subjek / Judul Pertanyaan'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('body')
                            ->label(__('Isi Pertanyaan'))
                            ->rows(6)
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('ip_address')
                            ->label(__('IP Address Pengirim'))
                            ->disabled()
                            ->dehydrated(false),
                    ]),
            ]);
    }
}
