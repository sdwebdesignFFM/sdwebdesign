<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->plugin(
                SpatieTranslatablePlugin::make()
                    ->defaultLocales(['de', 'en'])
                    ->persist()
            )
            ->navigationGroups([
                NavigationGroup::make('Hauptseiten')
                    ->collapsed(false),
                NavigationGroup::make('Leistungen'),
                NavigationGroup::make('Referenzen'),
                NavigationGroup::make('Ratgeber'),
                NavigationGroup::make('Lokale Seiten'),
                NavigationGroup::make('Blog'),
                NavigationGroup::make('System')
                    ->collapsed(true),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn (): string => Blade::render('
                    <div class="flex items-center gap-1">
                        <a href="{{ route(\'filament.admin.resources.work-logs.index\') }}?tableAction=create"
                           class="fi-icon-btn fi-icon-btn-size-md inline-flex items-center justify-center gap-x-2 rounded-lg px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-500/5 dark:text-gray-200 dark:hover:bg-gray-400/5"
                           title="Neue Arbeitszeit">
                            <x-heroicon-o-clock class="h-5 w-5" />
                            <span class="hidden sm:inline">+Zeit</span>
                        </a>
                        <a href="{{ route(\'filament.admin.resources.tasks.create\') }}"
                           class="fi-icon-btn fi-icon-btn-size-md inline-flex items-center justify-center gap-x-2 rounded-lg px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-500/5 dark:text-gray-200 dark:hover:bg-gray-400/5"
                           title="Neue Aufgabe">
                            <x-heroicon-o-clipboard-document-list class="h-5 w-5" />
                            <span class="hidden sm:inline">+Aufgabe</span>
                        </a>
                    </div>
                '),
            );
    }
}
