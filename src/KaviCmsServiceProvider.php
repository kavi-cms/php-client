<?php

namespace Kavicms\KavicmsLaravel;

use Illuminate\Support\ServiceProvider;
use Kavicms\KavicmsLaravel\Interfaces\IKaviCmsCacheReader;

class KaviCmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(IKaviCmsCacheReader::class, function () {
            return new KaviCmsCacheReader();
        });
    }

    public function boot(): void
    {
        $cmsWriter = new KaviCmsCacheWriter();
        $cmsWriter->initCaches();
    }
}
