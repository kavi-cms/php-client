<?php

namespace Kavicms\KavicmsLaravel\Models;

class PageTemplate
{
    public int $id;
    public string $key;
    public string $name;
    public string $viewName;
    public int $layoutId;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
