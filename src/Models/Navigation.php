<?php

namespace Kavicms\KavicmsLaravel\Models;

class Navigation
{
    private array $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    protected function getData(): array
    {
        return $this->data;
    }

    public function get(string $key): array
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } else {
            throw new \Exception("Unknown navigation key. Here are the possible keys: " . json_encode(array_keys($this->data), true));
        }
    }
}
