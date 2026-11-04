<?php

namespace Tests\Feature;

use App\Enums\PageStatus;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\QuestionTopic;
use App\Models\Setting;
use App\Models\Slide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_active_categories(): void
    {
        Category::create(['name' => 'Kategori 1', 'slug' => 'kategori-1', 'is_active' => true]);
        Category::create(['name' => 'Kategori Nonaktif', 'slug' => 'kategori-nonaktif', 'is_active' => false]);

        $response = $this->getJson('/api/v1/categories');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'kategori-1');
    }

    public function test_can_get_published_page(): void
    {
        Page::create([
            'title' => 'Tentang Kami',
            'slug' => 'tentang-kami',
            'content' => '<p>Informasi instansi kami</p>',
            'status' => PageStatus::Published,
        ]);

        $response = $this->getJson('/api/v1/pages/tentang-kami');

        $response->assertOk()
            ->assertJsonPath('data.slug', 'tentang-kami')
            ->assertJsonPath('data.title', 'Tentang Kami');
    }

    public function test_can_list_active_slides(): void
    {
        Slide::create([
            'title' => 'Slide Aktif',
            'image' => 'slides/banner.jpg',
            'is_active' => true,
        ]);
        Slide::create([
            'title' => 'Slide Mati',
            'image' => 'slides/banner2.jpg',
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/v1/slides');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Slide Aktif');
    }

    public function test_can_get_menu_by_location(): void
    {
        $menu = Menu::create(['name' => 'Header Menu', 'location' => 'header', 'is_active' => true]);
        MenuItem::create([
            'menu_id' => $menu->id,
            'label' => 'Beranda',
            'url' => '/',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/menus/header');

        $response->assertOk()
            ->assertJsonPath('data.name', 'Header Menu')
            ->assertJsonPath('data.items.0.label', 'Beranda');
    }

    public function test_can_get_settings(): void
    {
        Setting::create(['group' => 'general', 'key' => 'site_name', 'value' => 'Portal Resmi', 'type' => 'text']);

        $response = $this->getJson('/api/v1/settings');

        $response->assertOk()
            ->assertJsonPath('dictionary.site_name', 'Portal Resmi');
    }

    public function test_can_list_faqs_and_topics(): void
    {
        $topic = QuestionTopic::create(['name' => 'Layanan', 'slug' => 'layanan', 'is_active' => true]);
        Faq::create([
            'topic_id' => $topic->id,
            'question' => 'Jam berapa buka?',
            'answer' => '<p>08.00 - 16.00</p>',
            'is_active' => true,
        ]);

        $responseTopic = $this->getJson('/api/v1/question-topics');
        $responseTopic->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'layanan');

        $responseFaq = $this->getJson('/api/v1/faqs');
        $responseFaq->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.question', 'Jam berapa buka?');
    }
}
