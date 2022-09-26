<?php

namespace App\Infrastructure\Laravel\Provider;

use App\Domain;
use App\Infrastructure;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repositories
        $this->app->bind(Domain\User\UserRepository::class, Infrastructure\User\UserRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
