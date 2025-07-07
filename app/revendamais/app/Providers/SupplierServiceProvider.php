<?php

namespace App\Providers;

use App\Repositories\Interfaces\SupplierRepositoryInterface;
use App\Repositories\SupplierRepository;
use Illuminate\Support\ServiceProvider;
use App\Services\Contracts\SupplierServiceInterface;
use App\Services\SupplierService;
class SupplierServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SupplierServiceInterface::class, SupplierService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
