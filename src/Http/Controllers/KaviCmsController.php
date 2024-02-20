<?php

namespace Kavicms\KavicmsLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Kavicms\KavicmsLaravel\Interfaces\IKaviCmsCacheReader;

class KaviCmsController extends Controller
{
    public function index(Request $request, IKaviCmsCacheReader $cmsCacheReader): View
    {
        return $cmsCacheReader->getPageByPath($request->path());
    }
}
