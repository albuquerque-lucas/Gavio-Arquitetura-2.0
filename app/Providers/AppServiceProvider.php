<?php

namespace App\Providers;

use App\Models\SiteAsset;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $assets = [
                'home_background_url' => asset('storage/pages/background-body-public.jpg'),
                'brand_logo_icon_url' => asset('storage/logo/gavioarquitetura-icone-02.png'),
                'brand_logo_written_url' => asset('storage/logo/gavioarquitetura-escrita-01.png'),
                'home_background_name' => null,
                'brand_logo_icon_name' => null,
                'brand_logo_written_name' => null,
                'project_cover_fallback_url' => null,
                'project_cover_fallback_name' => null,
            ];

            try {
                if (! Schema::hasTable('site_assets')) {
                    $view->with('assets', $assets);

                    return;
                }

                $rows = SiteAsset::whereIn('key', [
                    'home_background',
                    'brand_logo_icon',
                    'brand_logo_written',
                    'project_cover_fallback',
                    'brand_logo_primary',
                    'brand_logo_secondary',
                ])->get()->keyBy('key');

                $home = $rows->get('home_background');
                $icon = $rows->get('brand_logo_icon') ?? $rows->get('brand_logo_primary');
                $written = $rows->get('brand_logo_written') ?? $rows->get('brand_logo_secondary');
                $projectCoverFallback = $rows->get('project_cover_fallback');

                if ($home && ! empty($home->path)) {
                    $assets['home_background_url'] = asset(ltrim($home->path, '/'));
                    $assets['home_background_name'] = $home->original_name;
                }
                if ($icon && ! empty($icon->path)) {
                    $assets['brand_logo_icon_url'] = asset(ltrim($icon->path, '/'));
                    $assets['brand_logo_icon_name'] = $icon->original_name;
                }
                if ($written && ! empty($written->path)) {
                    $assets['brand_logo_written_url'] = asset(ltrim($written->path, '/'));
                    $assets['brand_logo_written_name'] = $written->original_name;
                }
                if ($projectCoverFallback && ! empty($projectCoverFallback->path)) {
                    $assets['project_cover_fallback_url'] = asset(ltrim($projectCoverFallback->path, '/'));
                    $assets['project_cover_fallback_name'] = $projectCoverFallback->original_name;
                }
            } catch (Throwable $e) {
                // Keep fallback assets when DB isn't ready.
            }

            $view->with('assets', $assets);
        });
    }
}
