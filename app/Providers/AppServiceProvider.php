<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\ApiResponseMiddleware;
use Illuminate\Support\Facades\Route;
use App\Services\ArticleService;
use App\Services\ArticleServiceImpl;
use App\Repository\ArticleRepository;
use App\Repository\ArticleRepositoryImpl;
use App\Repository\ClientRepository;
use App\Repository\ClientRepositoryImpl;
use App\Services\ClientServiceImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(ArticleService::class, ArticleServiceImpl::class);
        $this->app->bind(ArticleRepository::class, ArticleRepositoryImpl::class);
        $this->app->bind(ClientRepository::class, ClientRepositoryImpl::class);
        $this->app->bind('ClientService', ClientServiceImpl::class);


            
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Route::middleware([ApiResponseMiddleware::class])->group(function () {
            // Middleware appliqué à toutes les routes
        });
    }
}
