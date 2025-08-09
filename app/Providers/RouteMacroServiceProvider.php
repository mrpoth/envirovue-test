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
        Route::macro('softDeletes', function (string $uri, string $controller) {
            Route::get("$uri/trashed", [$controller, 'trashed'])->name("$uri.trashed");
            Route::patch("$uri/{user}/restore", [$controller, 'restore'])->name("$uri.restore");
            Route::delete("$uri/{user}/delete", [$controller, 'delete'])->name("$uri.delete");
        });
    }
}
