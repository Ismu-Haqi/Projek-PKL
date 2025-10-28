<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

class AppearanceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share appearance settings with all views
        View::composer('*', function ($view) {
            // Cache settings for 1 hour to improve performance
            $appearanceSettings = cache()->remember('appearance_settings', 3600, function () {
                return [
                    'theme' => Setting::where('key', 'theme')->value('value') ?? 'light',
                    'accent_color' => Setting::where('key', 'accent_color')->value('value') ?? 'blue',
                    'sidebar_style' => Setting::where('key', 'sidebar_style')->value('value') ?? 'fixed',
                    'navbar_position' => Setting::where('key', 'navbar_position')->value('value') ?? 'fixed',
                    'font_size' => Setting::where('key', 'font_size')->value('value') ?? 'medium',
                    'animation_speed' => Setting::where('key', 'animation_speed')->value('value') ?? 'normal',
                    'text_size' => Setting::where('key', 'text_size')->value('value') ?? 'md', // NEW
                    'show_breadcrumbs' => Setting::where('key', 'show_breadcrumbs')->value('value') ?? '1',
                    'show_notifications' => Setting::where('key', 'show_notifications')->value('value') ?? '1',
                    'compact_mode' => Setting::where('key', 'compact_mode')->value('value') ?? '0',
                    'smooth_scrolling' => Setting::where('key', 'smooth_scrolling')->value('value') ?? '1',
                ];
            });

            $view->with('appearanceSettings', $appearanceSettings);
        });
    }
}