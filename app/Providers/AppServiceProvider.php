<?php

namespace App\Providers;

<<<<<<< HEAD
=======
use Illuminate\Routing\UrlGenerator;
>>>>>>> origin/master
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
<<<<<<< HEAD
     */
    public function register(): void
=======
     *
     * @return void
     */
    public function register()
>>>>>>> origin/master
    {
        //
    }

    /**
     * Bootstrap any application services.
<<<<<<< HEAD
     */
    public function boot(): void
    {
        //
=======
     *
     * @param UrlGenerator $url
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (env('APP_ENV') == 'production') {
            $url->forceScheme('https');
        }
>>>>>>> origin/master
    }
}
