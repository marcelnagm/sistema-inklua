<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('path.public', function() {
            return base_path().'/html';
        });

        Paginator::useBootstrap();
        
        View::composer('layouts.cms', function ($view) {
            $logged = \Auth::user();
            $view->with('logged', $logged);
        });

        // if($this->app->environment('production')) {
        //     \URL::forceScheme('https');
        // }
        
    }
}
