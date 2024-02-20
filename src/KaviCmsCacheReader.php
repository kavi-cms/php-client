<?php


namespace Kavicms\KavicmsLaravel;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Kavicms\KavicmsLaravel\Interfaces\IKaviCmsCacheReader;
use Kavicms\KavicmsLaravel\Models\GetPageByIdResponse;
use Kavicms\KavicmsLaravel\Models\Navigation;
use Kavicms\KavicmsLaravel\Models\PageLayout;
use Kavicms\KavicmsLaravel\Models\PageTemplate;

class KaviCmsCacheReader implements IKaviCmsCacheReader
{
    function getPageByPath(string $path): View
    {
        /**
         * @var GetPageByIdResponse $pageById
         * @var PageTemplate $template
         * @var PageLayout $layout
         */

        $path = strlen($path) === 1 ? $path : "/" . $path;
        $linksToCache = Cache::get("kavicms/links");
        if (!key_exists($path, $linksToCache)) {
            abort(404);
        }

        $pageById = Cache::get("kavicms/pages/$linksToCache[$path]");
        $template = Cache::get("kavicms/templates")[$pageById->templateId];
        $layout = Cache::get("kavicms/pagelayouts")[$template->layoutId];

        $nav = [];
        foreach ($layout->navigationGroups as $navigationGroup) {
            $key = $navigationGroup['key'];
            $languageCode = $pageById->language->languageCode;
            $nav[$navigationGroup['key']] = Cache::get("kavicms/navigations/$key/$languageCode");
        }
        $navigation = new Navigation($nav);

        return view($template->viewName, ['page' => $pageById, 'nav' => $navigation]);
    }
}
