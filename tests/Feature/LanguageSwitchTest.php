<?php

namespace Tests\Feature;

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Tests\TestCase;

class LanguageSwitchTest extends TestCase
{
    public function test_language_switch_has_configured_locales(): void
    {
        $switch = LanguageSwitch::make();

        $this->assertEquals(['id', 'en'], $switch->getLocales());
        $this->assertEquals('Bahasa Indonesia', $switch->getLabels()['id'] ?? null);
        $this->assertEquals('English', $switch->getLabels()['en'] ?? null);
        $this->assertStringContainsString('flags/id.svg', $switch->getFlags()['id'] ?? '');
        $this->assertStringContainsString('flags/en.svg', $switch->getFlags()['en'] ?? '');
        $this->assertTrue($switch->isCircular());
    }

    public function test_admin_login_page_renders_with_language_switch(): void
    {
        $response = $this->get('/admin/login');

        $response->assertOk();
        $response->assertSeeLivewire('language-switch-component');
    }

    public function test_locale_switches_based_on_session(): void
    {
        $this->withSession(['locale' => 'en'])
            ->get('/admin/login');

        $this->assertEquals('en', app()->getLocale());

        $this->withSession(['locale' => 'id'])
            ->get('/admin/login');

        $this->assertEquals('id', app()->getLocale());
    }

    public function test_panel_brand_and_navigation_groups_support_english(): void
    {
        $panel = filament()->getPanel('admin');

        app()->setLocale('en');
        $this->assertEquals('CMS & Services Portal', $panel->getBrandName());
        $groups = $panel->getNavigationGroups();
        $this->assertEquals('Content & News', $groups['Konten & Berita']->getLabel());
        $this->assertEquals('Appearance & Navigation', $groups['Tampilan & Navigasi']->getLabel());
        $this->assertEquals('Public Services', $groups['Layanan Masyarakat']->getLabel());
        $this->assertEquals('Settings', $groups['Pengaturan']->getLabel());

        app()->setLocale('id');
        $this->assertEquals('Portal CMS & Layanan', $panel->getBrandName());
        $this->assertEquals('Konten & Berita', $groups['Konten & Berita']->getLabel());
        $this->assertEquals('Tampilan & Navigasi', $groups['Tampilan & Navigasi']->getLabel());
        $this->assertEquals('Layanan Masyarakat', $groups['Layanan Masyarakat']->getLabel());
        $this->assertEquals('Pengaturan', $groups['Pengaturan']->getLabel());
    }
}
