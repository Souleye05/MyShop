<?php

namespace App\Providers;

use App\Services\AuthentificationPassport;
use App\Services\AuthentificationSanctum;
use App\Services\AuthenticationServiceInterface;
use Illuminate\Support\ServiceProvider;

class AuthCustomServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Choisir dynamiquement entre Passport et Sanctum selon une condition
        /* $this->app->bind(AuthenticationServiceInterface::class, function ($app) {
            // Logique pour choisir quel service utiliser
            // Par exemple, une configuration ou une variable d'environnement
            if (env('AUTH_DRIVER') === 'passport') {
                return new AuthentificationPassport();
            } else {
                return new AuthentificationSanctum();
            }
        }); */
        $this->app->bind(AuthenticationServiceInterface::class, AuthentificationPassport::class);
        // Autres services à ajouter si nécessaires
        // $this->app->bind(AuthenticationServiceInterface::class, AuthentificationSanctum::class);
    }

    public function boot()
    {
        //
    }
}
