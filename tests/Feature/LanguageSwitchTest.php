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
}
