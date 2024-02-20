<?php

namespace Kavicms\KavicmsLaravel\Interfaces;

use Illuminate\View\View;

interface IKaviCmsCacheReader
{
    function getPageByPath(string $path): View;
}
