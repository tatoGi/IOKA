<?php

namespace App\Providers;

use App\Models\Partner;
use App\Repositories\Interface\PageRepositoryInterface;
use App\Repositories\PageRepository;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        View::composer(['home', 'about'], function ($view) {
            $view->with('partners', Partner::latest()->get());
        });
    }
}
