<?php

namespace Kavicms\KavicmsLaravel\Models;

class GetPagesResponse
{
    public string $webSiteName;
    public string $webSiteDomain;
    public array $pageIdList;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
}
