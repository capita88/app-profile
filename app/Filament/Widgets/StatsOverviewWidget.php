<?php

namespace App\Filament\Widgets;

use App\Enums\PostStatus;
use App\Enums\QuestionStatus;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Question;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $publishedPosts = Post::where('status', PostStatus::Published)->count();
        $totalQuestions = Question::count();
        $pendingQuestions = Question::where('status', QuestionStatus::Pending)->count();
        $activeFaqs = Faq::where('is_active', true)->count();

        return [
            Stat::make(__('Artikel Terbit'), $publishedPosts)
                ->description(__('Total artikel aktif di portal'))
                ->descriptionIcon(Heroicon::OutlinedDocumentText)
                ->color('success'),
            Stat::make(__('Pertanyaan Masuk'), $totalQuestions)
                ->description(__('Aspirasi dari masyarakat'))
                ->descriptionIcon(Heroicon::OutlinedChatBubbleBottomCenterText)
                ->color('primary'),
            Stat::make(__('Perlu Ditanggapi'), $pendingQuestions)
                ->description(__('Menunggu respon petugas'))
                ->descriptionIcon(Heroicon::OutlinedClock)
                ->color($pendingQuestions > 0 ? 'warning' : 'gray'),
            Stat::make(__('FAQ Aktif'), $activeFaqs)
                ->description(__('Informasi tanya jawab publik'))
                ->descriptionIcon(Heroicon::OutlinedInformationCircle)
                ->color('info'),
        ];
    }
}
