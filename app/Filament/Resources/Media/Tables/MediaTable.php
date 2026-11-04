<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file_path')
                    ->label('Pratinjau')
                    ->disk('public')
                    ->square(),
                TextColumn::make('file_name')
                    ->label('Nama File')
                    ->searchable()
                    ->sortable()
                    ->default(fn ($record) => basename($record->file_path)),
                TextColumn::make('mime_type')
                    ->label('Tipe')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('file_size')
                    ->label('Ukuran')
                    ->formatStateUsing(function ($state) {
                        if (! $state) {
                            return '-';
                        }
                        if ($state < 1024) {
                            return "{$state} B";
                        }
                        if ($state < 1048576) {
                            return round($state / 1024, 1).' KB';
                        }

                        return round($state / 1048576, 1).' MB';
                    }),
                TextColumn::make('user.name')
                    ->label('Pengunggah')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Diunggah')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
