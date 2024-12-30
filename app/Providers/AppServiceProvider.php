<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interface\PageRepositoryInterface;
use App\Repositories\PageRepository;

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
    }
}