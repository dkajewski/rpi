<?php

namespace App\Providers;

use App\Interfaces\Repository\NotesRepositoryInterface;
use App\Interfaces\Repository\WeatherRepositoryInterface;
use App\Repositories\NotesRepository;
use App\Repositories\WeatherRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(WeatherRepositoryInterface::class, WeatherRepository::class);
        $this->app->bind(NotesRepositoryInterface::class, NotesRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
