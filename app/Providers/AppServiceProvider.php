<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        config(['app.locale' => 'id']);
	    Carbon::setLocale('id');
    }
}
