<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;

use App\Repositories\Contracts\SupplierRepositoryInterface;
use App\Repositories\SupplierRepository;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\ProductRepository;

use App\Repositories\Contracts\RestockRepositoryInterface;
use App\Repositories\RestockRepository;

use App\Repositories\Contracts\StockOpnameRepositoryInterface;
use App\Repositories\StockOpnameRepository;

use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(RestockRepositoryInterface::class, RestockRepository::class);
        $this->app->bind(StockOpnameRepositoryInterface::class, StockOpnameRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
    }

    public function boot(): void
    {
        //
    }
}