<?php

namespace Kavicms\KavicmsLaravel;

use Illuminate\Support\Facades\Cache;
use Kavicms\KavicmsLaravel\Interfaces\IKaviCmsCacheWriter;

class KaviCmsCacheWriter implements IKaviCmsCacheWriter
{
    private KaviCmsApiClient $apiClient;

    public function __construct()
    {
        $this->apiClient = new KaviCmsApiClient();
    }

    function initCaches(): void
    {
        error_log(Cache::has("kavicms/init") ? "Cache Hit" : "Cache Miss" );
        if (!Cache::has("kavicms/init")) {
            $this->putLanguagesToCache();
            $this->putTemplatesToCache();
            $this->putLayoutsToCache();
            $this->putPagesToCache();
            $this->putNavigationGroups();
            Cache::put("kavicms/init", true);
        }
    }

    private function putLanguagesToCache(): void
    {
        $data = $this->apiClient->getLanguages();
        Cache::put("kavicms/languages", $data);
    }

    private function putTemplatesToCache(): void
    {
        $data = $this->apiClient->getTemplates();
        Cache::put("kavicms/templates", $data);
    }

    private function putLayoutsToCache(): void
    {
        $data = $this->apiClient->getLayouts();
        Cache::put("kavicms/pagelayouts", $data);
    }

    private function putPagesToCache(): void
    {
        $pagesResponse = $this->apiClient->getPages();

        $linkToPageId = [];
        foreach ($pagesResponse->pageIdList as $id) {
            $pageById = $this->apiClient->getPageById($id);
            Cache::put("kavicms/pages/$id", $pageById);

            foreach ($pageById->links as $link) {
                $linkToPageId[$link] = $id;
            }
        }

        $this->putLinksToCache($linkToPageId);
    }

    private function putLinksToCache(array $links): void
    {
        Cache::put("kavicms/links", $links);
    }

    private function putNavigationGroups(): void
    {
        // todo new url needed
        $languages = Cache::get("kavicms/languages");
        $pageLayouts = Cache::get("kavicms/pagelayouts");

        foreach ($languages as $language) {
            foreach ($pageLayouts as $pageLayout) {
                foreach ($pageLayout->navigationGroups as $navigationGroup) {
                    $data = $this->apiClient->getNavigationGroups($navigationGroup['key'], $language->languageCode);
                    $path = "kavicms/navigations/" . $navigationGroup["key"] . "/" . $language->languageCode;
                    Cache::put($path, $data);
                }
            }
        }
    }


}
