<?php

namespace App\Providers;

use App\Repositories\Contracts\StudyClubRepositoryInterface;
use App\Repositories\Eloquent\StudyClubRepository;
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
        // Bind StudyClub Repository (DDD)
        $this->app->bind(
            StudyClubRepositoryInterface::class,
            StudyClubRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
    }
}
