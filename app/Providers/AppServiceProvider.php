<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // uncomment this if want to see query log
        // DB::listen(function($query) {
        //     File::append(
        //         storage_path('/logs/query.log'),
        //         $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL
        //    );
        // });
    }
}
