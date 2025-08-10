<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Route::macro('softDeletes', function (string $uri, string $controller, string $param = 'id') {
            Route::get("$uri/trashed", [$controller, 'trashed'])->name("$uri.trashed");
            Route::patch("$uri/{{$param}}/restore", [$controller, 'restore'])->name("$uri.restore");
            Route::delete("$uri/{{$param}}/delete", [$controller, 'delete'])->name("$uri.delete");
        });
    }
}
