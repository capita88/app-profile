<?php

namespace Tests\Feature;

use App\Enums\AnswerStatus;
use App\Enums\MenuItemType;
use App\Enums\MenuLocation;
use App\Enums\PageStatus;
use App\Enums\PostStatus;
use App\Enums\QuestionStatus;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Resources\Media\MediaResource;
use App\Filament\Resources\Menus\MenuResource;
use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Questions\QuestionResource;
use App\Filament\Resources\Questions\RelationManagers\AnswersRelationManager;
use App\Filament\Resources\QuestionTopics\QuestionTopicResource;
use App\Filament\Resources\Settings\SettingResource;
use App\Filament\Resources\Slides\SlideResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Widgets\LatestPostsWidget;
use App\Filament\Widgets\LatestQuestionsWidget;
use Tests\TestCase;

class FilamentLocalizationTest extends TestCase
{
    public function test_resources_labels_are_localized_in_indonesian(): void
    {
        app()->setLocale('id');

        $this->assertEquals('Kategori', CategoryResource::getModelLabel());
        $this->assertEquals('Kategori', CategoryResource::getPluralModelLabel());
        $this->assertEquals('Kategori', CategoryResource::getNavigationLabel());

        $this->assertEquals('Artikel', PostResource::getModelLabel());
        $this->assertEquals('Artikel / Berita', PostResource::getPluralModelLabel());
        $this->assertEquals('Artikel / Berita', PostResource::getNavigationLabel());

        $this->assertEquals('Halaman Statis', PageResource::getModelLabel());
        $this->assertEquals('Halaman Statis', PageResource::getPluralModelLabel());

        $this->assertEquals('Media', MediaResource::getModelLabel());
        $this->assertEquals('Pustaka Media', MediaResource::getPluralModelLabel());

        $this->assertEquals('Menu Navigasi', MenuResource::getModelLabel());
        $this->assertEquals('Menu Navigasi', MenuResource::getPluralModelLabel());

        $this->assertEquals('Slide Banner', SlideResource::getModelLabel());
        $this->assertEquals('Slide Banner', SlideResource::getPluralModelLabel());

        $this->assertEquals('Pertanyaan Masyarakat', QuestionResource::getModelLabel());
        $this->assertEquals('Pertanyaan Masyarakat', QuestionResource::getPluralModelLabel());

        $this->assertEquals('Topik Pertanyaan', QuestionTopicResource::getModelLabel());
        $this->assertEquals('Topik Pertanyaan', QuestionTopicResource::getPluralModelLabel());

        $this->assertEquals('FAQ', FaqResource::getModelLabel());
        $this->assertEquals('FAQ', FaqResource::getPluralModelLabel());

        $this->assertEquals('Pengaturan', SettingResource::getModelLabel());
        $this->assertEquals('Pengaturan Situs', SettingResource::getPluralModelLabel());

        $this->assertEquals('Pengguna', UserResource::getModelLabel());
        $this->assertEquals('Pengguna & Petugas', UserResource::getPluralModelLabel());

        $this->assertEquals('Jawaban Resmi', AnswersRelationManager::getModelLabel());
        $this->assertEquals('Jawaban Resmi', AnswersRelationManager::getPluralModelLabel());
    }

    public function test_resources_labels_are_localized_in_english(): void
    {
        app()->setLocale('en');

        $this->assertEquals('Categories', CategoryResource::getModelLabel());
        $this->assertEquals('Categories', CategoryResource::getPluralModelLabel());
        $this->assertEquals('Categories', CategoryResource::getNavigationLabel());

        $this->assertEquals('Articles', PostResource::getModelLabel());
        $this->assertEquals('Articles / News', PostResource::getPluralModelLabel());
        $this->assertEquals('Articles / News', PostResource::getNavigationLabel());

        $this->assertEquals('Static Pages', PageResource::getModelLabel());
        $this->assertEquals('Static Pages', PageResource::getPluralModelLabel());

        $this->assertEquals('Media', MediaResource::getModelLabel());
        $this->assertEquals('Media Library', MediaResource::getPluralModelLabel());

        $this->assertEquals('Navigation Menus', MenuResource::getModelLabel());
        $this->assertEquals('Navigation Menus', MenuResource::getPluralModelLabel());

        $this->assertEquals('Banner Slides', SlideResource::getModelLabel());
        $this->assertEquals('Banner Slides', SlideResource::getPluralModelLabel());

        $this->assertEquals('Public Inquiries', QuestionResource::getModelLabel());
        $this->assertEquals('Public Inquiries', QuestionResource::getPluralModelLabel());

        $this->assertEquals('Inquiry Topics', QuestionTopicResource::getModelLabel());
        $this->assertEquals('Inquiry Topics', QuestionTopicResource::getPluralModelLabel());

        $this->assertEquals('FAQ', FaqResource::getModelLabel());
        $this->assertEquals('FAQ', FaqResource::getPluralModelLabel());

        $this->assertEquals('Settings', SettingResource::getModelLabel());
        $this->assertEquals('Site Settings', SettingResource::getPluralModelLabel());

        $this->assertEquals('Users', UserResource::getModelLabel());
        $this->assertEquals('Users & Staff', UserResource::getPluralModelLabel());

        $this->assertEquals('Official Answers', AnswersRelationManager::getModelLabel());
        $this->assertEquals('Official Answers', AnswersRelationManager::getPluralModelLabel());
    }

    public function test_navigation_tree_items_support_english(): void
    {
        app()->setLocale('en');
        $panel = filament()->getPanel('admin');
        $navigation = $panel->getNavigation();

        $groupLabels = [];
        $itemLabels = [];

        foreach ($navigation as $group) {
            $groupLabels[] = $group->getLabel();
            foreach ($group->getItems() as $item) {
                $itemLabels[] = $item->getLabel();
            }
        }

        $this->assertContains('Content & News', $groupLabels);
        $this->assertContains('Appearance & Navigation', $groupLabels);
        $this->assertContains('Public Services', $groupLabels);
        $this->assertContains('Settings', $groupLabels);

        $this->assertContains('Articles / News', $itemLabels);
        $this->assertContains('Categories', $itemLabels);
        $this->assertContains('Static Pages', $itemLabels);
        $this->assertContains('Media Library', $itemLabels);
        $this->assertContains('Navigation Menus', $itemLabels);
        $this->assertContains('Banner Slides', $itemLabels);
        $this->assertContains('Public Inquiries', $itemLabels);
        $this->assertContains('Inquiry Topics', $itemLabels);
        $this->assertContains('Site Settings', $itemLabels);
        $this->assertContains('Users & Staff', $itemLabels);
    }

    public function test_widgets_heading_are_localized(): void
    {
        app()->setLocale('en');
        $this->assertEquals('Latest Articles & News', (new LatestPostsWidget)->getTableHeading());
        $this->assertEquals('Latest Public Inquiries', (new LatestQuestionsWidget)->getTableHeading());

        app()->setLocale('id');
        $this->assertEquals('Artikel & Berita Terbaru', (new LatestPostsWidget)->getTableHeading());
        $this->assertEquals('Pertanyaan Masyarakat Terbaru', (new LatestQuestionsWidget)->getTableHeading());
    }

    public function test_enums_are_localized(): void
    {
        app()->setLocale('en');
        $this->assertEquals('Pending Answer', QuestionStatus::Pending->getLabel());
        $this->assertEquals('Answered', QuestionStatus::Answered->getLabel());
        $this->assertEquals('Rejected', QuestionStatus::Rejected->getLabel());
        $this->assertEquals('Published', AnswerStatus::Published->getLabel());
        $this->assertEquals('Custom URL', MenuItemType::Custom->getLabel());
        $this->assertEquals('Header (Top Navigation)', MenuLocation::Header->getLabel());
        $this->assertEquals('Published', PostStatus::Published->getLabel());
        $this->assertEquals('Published', PageStatus::Published->getLabel());

        app()->setLocale('id');
        $this->assertEquals('Menunggu Jawaban', QuestionStatus::Pending->getLabel());
        $this->assertEquals('Sudah Dijawab', QuestionStatus::Answered->getLabel());
        $this->assertEquals('Ditolak', QuestionStatus::Rejected->getLabel());
        $this->assertEquals('Dipublikasikan', AnswerStatus::Published->getLabel());
        $this->assertEquals('Kustom URL', MenuItemType::Custom->getLabel());
        $this->assertEquals('Header (Navigasi Atas)', MenuLocation::Header->getLabel());
        $this->assertEquals('Dipublikasikan', PostStatus::Published->getLabel());
        $this->assertEquals('Dipublikasikan', PageStatus::Published->getLabel());
    }
}

