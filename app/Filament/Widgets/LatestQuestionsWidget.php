<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Questions\QuestionResource;
use App\Models\Question;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class LatestQuestionsWidget extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): string|Htmlable|null
    {
        return __('Pertanyaan Masyarakat Terbaru');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Question::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('Tanggal'))
                    ->dateTime('d M Y H:i'),
                TextColumn::make('name')
                    ->label(__('Penanya')),
                TextColumn::make('subject')
                    ->label(__('Subjek'))
                    ->limit(40),
                TextColumn::make('topic.name')
                    ->label(__('Topik'))
                    ->badge()
                    ->color('info'),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
            ])
            ->paginated(false)
            ->recordActions([
                Action::make('review')
                    ->label(__('Lihat & Tanggapi'))
                    ->url(fn (Question $record): string => QuestionResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
