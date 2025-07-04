<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\SupplierRepositoryInterface;
use App\Repositories\SupplierRepository;
use App\Services\Contracts\ExternalSearchServiceInterface;
use App\Services\ExternalSearchService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(ExternalSearchServiceInterface::class, ExternalSearchService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
