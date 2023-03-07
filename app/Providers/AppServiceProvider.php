<?php

namespace App\Providers;


use App\Models\Clients as ModelsClients;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
        View::share('clientTotal', ModelsClients::count());
        View::share('clients', ModelsClients::paginate('8'));
        Paginator::useBootstrap();
        View()->composer('*', function ($view) {
            $view->with('user', Auth::user());
        });
    }
}
