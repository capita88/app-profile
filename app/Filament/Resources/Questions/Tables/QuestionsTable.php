<?php

namespace App\Filament\Resources\Questions\Tables;

use App\Enums\QuestionStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('Tanggal Masuk'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('Penanya'))
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->email),
                TextColumn::make('subject')
                    ->label(__('Subjek Pertanyaan'))
                    ->searchable()
                    ->wrap()
                    ->limit(50),
                TextColumn::make('topic.name')
                    ->label(__('Topik'))
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
                TextColumn::make('answers_count')
                    ->label(__('Tanggapan'))
                    ->counts('answers')
                    ->badge()
                    ->color('success'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(QuestionStatus::class)
                    ->label(__('Filter Status')),
                SelectFilter::make('topic')
                    ->relationship('topic', 'name')
                    ->label(__('Filter Topik')),
            ])
            ->recordActions([
                EditAction::make()->label(__('Tinjau & Jawab')),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
