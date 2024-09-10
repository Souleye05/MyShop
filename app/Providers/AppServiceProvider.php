<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\ApiResponseMiddleware;
use Illuminate\Support\Facades\Route;
use App\Services\ArticleService;
use App\Observers\ClientObserver;
use App\Models\User;
use App\Models\Article;
use App\Models\Client;
use App\Policies\UserPolicy;
use App\Policies\ArticlePolicy;
use App\Policies\ClientPolicy;
use App\Services\uploaderPhoto;
use App\Services\ArticleServiceImpl;
use App\Repository\ArticleRepository;
use App\Repository\ArticleRepositoryImpl;
use App\Repository\ClientRepository;
use App\Repository\ClientRepositoryImpl;
use App\Services\ClientServiceImpl;
use App\Repository\UserRepositoryInterface;
use App\Repository\UserRepositoryImpl;
use App\Services\UserServiceImpl;
use App\Services\UserServiceInterface;
use App\Services\QRCodeService;

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
        $this->app->bind('uploaderPhoto', uploaderPhoto::class); 
        $this->app->bind('QRCodeService', QRCodeService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepositoryImpl::class);
        $this->app->bind(UserServiceInterface::class, UserServiceImpl::class);
    }

    protected $policies = [
        User::class => UserPolicy::class,
        Client::class => UserPolicy::class,
        Article::class => ArticlePolicy::class
    ];
    

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Route::middleware([ApiResponseMiddleware::class])->group(function () {
            // Middleware appliqué à toutes les routes
        });

        Client::observe(ClientObserver::class);
    }
}
