<?php

namespace App\Providers\Filament;

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class YesPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName(fn (): string => __('Portal CMS & Layanan'))
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->spa()
            ->resourceCreatePageRedirect('index')
            ->resourceEditPageRedirect('index')
            ->navigationGroups([
                'Konten & Berita' => NavigationGroup::make()
                    ->label(fn (): string => __('Konten & Berita')),
                'Tampilan & Navigasi' => NavigationGroup::make()
                    ->label(fn (): string => __('Tampilan & Navigasi')),
                'Layanan Masyarakat' => NavigationGroup::make()
                    ->label(fn (): string => __('Layanan Masyarakat')),
                'Pengaturan' => NavigationGroup::make()
                    ->label(fn (): string => __('Pengaturan')),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            // ->widgets([
            //     AccountWidget::class,
            //     FilamentInfoWidget::class,
            // ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch): void {
            $switch
                ->locales(['id', 'en'])
                ->labels([
                    'id' => 'Bahasa Indonesia',
                    'en' => 'English',
                ])
                ->visible(outsidePanels: true);
        });
    }
}
