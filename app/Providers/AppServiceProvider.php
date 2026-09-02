<?php

namespace App\Providers;

use App\Support\NewsRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Carbon::setLocale('id');

        View::composer('components.layout', function (\Illuminate\View\View $view) {
            $view->with('navCategories', NewsRepository::categories());
        });
    }
}
