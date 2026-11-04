<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->label(__('Kelompok'))
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('key')
                    ->label(__('Kunci (Key)'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('value')
                    ->label(__('Nilai'))
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('Tipe'))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('updated_at')
                    ->label(__('Terakhir Diubah'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('group', 'asc')
            ->filters([
                SelectFilter::make('group')
                    ->label(__('Filter Kelompok'))
                    ->options([
                        'general' => __('Umum'),
                        'contact' => __('Kontak'),
                        'social_media' => __('Media Sosial'),
                        'branding' => __('Branding'),
                    ]),
            ])
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
