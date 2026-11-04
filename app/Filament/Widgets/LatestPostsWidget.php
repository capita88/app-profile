<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class LatestPostsWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): string|Htmlable|null
    {
        return __('Artikel & Berita Terbaru');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Post::query()->with('category')->latest()->limit(5))
            ->columns([
                ImageColumn::make('image')
                    ->label(__('Foto'))
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label(__('Judul'))
                    ->limit(50),
                TextColumn::make('category.name')
                    ->label(__('Kategori'))
                    ->badge()
                    ->color('info'),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
                TextColumn::make('published_at')
                    ->label(__('Waktu Terbit'))
                    ->dateTime('d M Y H:i'),
            ])
            ->paginated(false)
            ->recordActions([
                Action::make('edit')
                    ->label(__('Ubah'))
                    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
