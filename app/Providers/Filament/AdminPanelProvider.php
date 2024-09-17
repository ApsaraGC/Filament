<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\Auth\Register;
use App\Filament\Pages\profile;
use App\Filament\Widgets\DasAdminInfoWidgets;
use App\Filament\Widgets\DasChartWidget;
use App\Filament\Widgets\DasDoctorInfoWidgets;
use App\Filament\Widgets\DasPatientInfoWidgets;
use Filament\Navigation\MenuItem;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->colors([
                'primary' => Color::Green,
            ])
            ->id('admin')
            ->path('admin')
            ->login()

             //->brandName('𝓗𝓪𝓷𝓪𝓰 𝓗𝓸𝓼𝓹𝓲𝓽𝓪𝓵')

            ->brandLogo(asset('images/hanags.png'))
            ->brandLogoHeight('60px')

            ->breadcrumbs(false)


            ->registration(Register::class)
            ->databaseNotifications()
            ->databaseNotificationsPolling('2s')
            ->widgets([
                DasDoctorInfoWidgets::class,
            ])

            ->widgets([
               DasPatientInfoWidgets::class,
            ])
            ->widgets([
                DasAdminInfoWidgets::class,
            ])
            ->widgets([
                
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Profile')
                    ->url(fn(): string => profile::getUrl())
                    ->icon('heroicon-o-user'),
            ])
            ->colors([
                'primary' => Color::Green,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')

            ->pages([

               Pages\Dashboard::class,
            ])
            ->colors([
                'primary' => Color::Green,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
               // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
            ]);
    }
}
