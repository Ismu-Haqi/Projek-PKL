<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\View;

class ApplyAppearanceSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get all appearance settings from database
        $appearanceSettings = [
            'theme' => Setting::where('key', 'theme')->value('value') ?? 'light',
            'accent_color' => Setting::where('key', 'accent_color')->value('value') ?? 'blue',
            'sidebar_style' => Setting::where('key', 'sidebar_style')->value('value') ?? 'fixed',
            'navbar_position' => Setting::where('key', 'navbar_position')->value('value') ?? 'fixed',
            'font_size' => Setting::where('key', 'font_size')->value('value') ?? 'medium',
            'animation_speed' => Setting::where('key', 'animation_speed')->value('value') ?? 'normal',
            'show_breadcrumbs' => Setting::where('key', 'show_breadcrumbs')->value('value') ?? '1',
            'show_notifications' => Setting::where('key', 'show_notifications')->value('value') ?? '1',
            'compact_mode' => Setting::where('key', 'compact_mode')->value('value') ?? '0',
            'smooth_scrolling' => Setting::where('key', 'smooth_scrolling')->value('value') ?? '1',
        ];

        // Share with all views
        View::share('appearanceSettings', $appearanceSettings);

        return $next($request);
    }
}