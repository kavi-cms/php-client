<?php

namespace Kavicms\KavicmsLaravel;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Kavicms\KavicmsLaravel\Interfaces\IKaviCmsCacheReader;

class KaviCmsRouteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Route::as('kavicms')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        });
    }
}
