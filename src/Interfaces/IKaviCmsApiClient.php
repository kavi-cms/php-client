<?php

namespace Kavicms\KavicmsLaravel\Interfaces;

use Kavicms\KavicmsLaravel\Models\GetPageByIdResponse;
use Kavicms\KavicmsLaravel\Models\GetPagesResponse;

interface IKaviCmsApiClient
{
    function getToken(): string;

    function getLanguages(): array;

    function getTemplates(): array;

    function getLayouts(): array;

    function getPages(): GetPagesResponse;

    function getPageById(int $id): GetPageByIdResponse;

    function getNavigationGroups(string $navigationGroupKey, string $languageCode): array;
}
