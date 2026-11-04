<?php

namespace App\Filament\Resources\Questions\RelationManagers;

use App\Enums\AnswerStatus;
use App\Enums\QuestionStatus;
use App\Models\QuestionAnswer;
use App\Notifications\QuestionAnsweredNotification;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification as FacadesNotification;

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Jawaban Resmi');
    }

    public static function getModelLabel(): ?string
    {
        return __('Jawaban Resmi');
    }

    public static function getPluralModelLabel(): ?string
    {
        return __('Jawaban Resmi');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label(__('Penjawab'))
                    ->relationship('user', 'name')
                    ->default(fn () => auth()->id())
                    ->required(),
                Grid::make(3)->schema([
                    Select::make('status')
                        ->label(__('Status'))
                        ->options(AnswerStatus::class)
                        ->default(AnswerStatus::Published)
                        ->required(),
                    Toggle::make('is_published')
                        ->label(__('Publikasikan ke Publik / Portal'))
                        ->default(true),
                    DateTimePicker::make('answered_at')
                        ->label(__('Waktu Menjawab'))
                        ->default(now()),
                ]),
                RichEditor::make('body')
                    ->label(__('Isi Tanggapan / Jawaban'))
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('Petugas Penjawab'))
                    ->searchable(),
                TextColumn::make('body')
                    ->label(__('Isi Jawaban'))
                    ->html()
                    ->limit(100),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
                IconColumn::make('is_published')
                    ->label(__('Publik'))
                    ->boolean(),
                TextColumn::make('answered_at')
                    ->label(__('Waktu Dijawab'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('Tulis Jawaban'))
                    ->after(function (QuestionAnswer $record) {
                        $question = $this->getOwnerRecord();
                        if ($record->is_published) {
                            $question->update(['status' => QuestionStatus::Answered]);
                        }

                        // Kirim notifikasi email ke penanya jika ada email
                        if (! empty($question->email)) {
                            try {
                                FacadesNotification::route('mail', $question->email)
                                    ->notify(new QuestionAnsweredNotification($question, $record));
                            } catch (\Throwable $e) {
                                // Ignore or log mail errors
                            }
                        }

                        Notification::make()
                            ->title(__('Jawaban Berhasil Disimpan'))
                            ->body(__('Status pertanyaan telah diperbarui menjadi dijawab.'))
                            ->success()
                            ->send();
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->after(function (QuestionAnswer $record) {
                        $question = $this->getOwnerRecord();
                        if ($record->is_published) {
                            $question->update(['status' => QuestionStatus::Answered]);
                        }
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
