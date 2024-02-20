<?php

namespace Kavicms\KavicmsLaravel\Models;

class PageLayout
{
    public int $id;
    public string $name;
    public string $layoutName;
    public array $navigationGroups;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
