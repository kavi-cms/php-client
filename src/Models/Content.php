<?php

namespace Kavicms\KavicmsLaravel\Models;

class Content
{
    public int $orderNumber;
    public string $uuid;
    public ?string $description;
    public array $dataDetail;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function get(string $key, $warn = true): string
    {
        if (array_key_exists($key, $this->dataDetail)) {
            return $this->dataDetail[$key];
        } else {
            if ($warn) {
                throw new \Exception("Unknown item key. Here are the possible keys: " .
                    json_encode(array_keys($this->dataDetail), true));
            } else {
                return "";
            }
        }
    }
}
