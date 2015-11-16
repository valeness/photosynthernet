<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {

        // Using class based composers...
        view()->composer(
            '*', 'App\Http\ViewComposers\BoarsComposer'
        );

        // Using Closure based composers...
        view()->composer('dashboard', function ($view) {

        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}