<?php

namespace Kavicms\KavicmsLaravel;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Kavicms\KavicmsLaravel\Interfaces\IKaviCmsApiClient;
use Kavicms\KavicmsLaravel\Models\GetPageByIdResponse;
use Kavicms\KavicmsLaravel\Models\GetPagesResponse;
use Kavicms\KavicmsLaravel\Models\Language;
use Kavicms\KavicmsLaravel\Models\NavigationGroup;
use Kavicms\KavicmsLaravel\Models\PageLayout;
use Kavicms\KavicmsLaravel\Models\PageTemplate;

class KaviCmsApiClient implements IKaviCmsApiClient
{
    private PendingRequest $api;
    private PendingRequest $auth;

    public function __construct()
    {
        $this->auth = Http::baseUrl(env('KAVICMS_AUTH_URL', 'http://auth.kavicms.com'));
        $this->api = Http::baseUrl(env('KAVICMS_API_URL', 'http://api.kavicms.com'))->withToken($this->getToken());
    }

    function getToken(): string
    {
        $token = (string)Cache::get('kavicms_token');
        if ($token) {
            return $token;
        }
        $res = $this->auth->asForm()->post("/oauth2/token", [
            'grant_type' => 'client_credentials',
            'client_id' => env('KAVICMS_CLIENT_ID'),
            'client_secret' => env('KAVICMS_CLIENT_SECRET'),
        ]);
        if (!$res->ok()) {
            dd("Veranda auth unauthorized");
        }
        $token = $res['access_token'];
        Cache::put('kavicms_token', $token, $res['expires_in']);
        return $token;
    }

    function getLanguages(): array
    {
        $res = $this->api->get("/languages")->json();
        $languages = [];
        foreach ($res as $language) {
            $languages[] = new Language($language);
        }
        return $languages;
    }

    function getTemplates(): array
    {
        $res = $this->api->get('/pagetemplates')->json();
        $pageTemplates = [];
        foreach ($res as $item) {
            $pageTemplates[$item['id']] = new PageTemplate($item);
        }
        return $pageTemplates;
    }

    function getLayouts(): array
    {
        $res = $this->api->get("/pagelayouts")->json();
        $pageLayouts = [];
        foreach ($res as $item) {
            $pageLayouts[$item['id']] = new PageLayout($item);
        }
        return $pageLayouts;
    }

    function getPages(): GetPagesResponse
    {
        $res = $this->api->get("/pages")->json();
        return new GetPagesResponse($res);
    }

    function getPageById(int $id): GetPageByIdResponse
    {
        $res = $this->api->get("/pages/$id")->json();
        return new GetPageByIdResponse($res);
    }

    function getPageByUrl(string $url): GetPageByIdResponse
    {
        $res = $this->api->get("/pages/query?pageUrl=$url")->json();

        return new GetPageByIdResponse($res);
    }

    function getNavigationGroups(string $navigationGroupKey, string $languageCode): array
    {
        $path = "/navigations/$navigationGroupKey/$languageCode";
        $res = $this->api->get($path)->json();

        $groupList = [];
        foreach ($res as $raw) {
            $groupList[$raw['id']] = new NavigationGroup($raw);
        }

        $onesWithParent = [];
        $onesWithoutParent = [];

        foreach ($groupList as $item) {
            if ($item->parentId != null) {
                $onesWithParent[$item->id] = $item;
            } else {
                $onesWithoutParent[$item->id] = $item;
            }
        }

        while (count($onesWithParent)) {
            foreach ($onesWithParent as $item) {
                if (NavigationGroup::findParentAddItem($onesWithoutParent, $item)) {
                    unset($onesWithParent[$item->id]);
                }
            }
        }

        return NavigationGroup::sort($onesWithoutParent);
    }
}
