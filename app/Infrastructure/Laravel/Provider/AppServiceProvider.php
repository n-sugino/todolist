<?php

namespace App\Infrastructure\Laravel\Provider;

use App\Domain;
use App\Infrastructure;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
          // Repositories
          $this->app->bind(Domain\Todo\TodoRepository::class, Infrastructure\Todo\TodoRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
