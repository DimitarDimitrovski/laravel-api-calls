<?php

namespace App\Providers;

use App\Http\Modules\API\CallerInterface;
use App\Http\Modules\API\ClientCallerImpl;
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
        $this->app->bind(CallerInterface::class, ClientCallerImpl::class);
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
