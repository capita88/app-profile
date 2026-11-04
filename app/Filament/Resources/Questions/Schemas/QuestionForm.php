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
                Section::make('Data Penanya & Topik')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Alamat Email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label('Nomor WhatsApp / HP')
                                ->tel()
                                ->maxLength(255),
                        ]),
                        Grid::make(3)->schema([
                            Select::make('topic_id')
                                ->label('Topik Pertanyaan')
                                ->relationship('topic', 'name')
                                ->searchable()
                                ->preload()
                                ->columnSpan(2),
                            Select::make('status')
                                ->label('Status Pertanyaan')
                                ->options(QuestionStatus::class)
                                ->default(QuestionStatus::Pending)
                                ->required()
                                ->columnSpan(1),
                        ]),
                    ]),
                Section::make('Uraian Pertanyaan')
                    ->schema([
                        TextInput::make('subject')
                            ->label('Subjek / Judul Pertanyaan')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('body')
                            ->label('Isi Pertanyaan')
                            ->rows(6)
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('ip_address')
                            ->label('IP Address Pengirim')
                            ->disabled()
                            ->dehydrated(false),
                    ]),
            ]);
    }
}
