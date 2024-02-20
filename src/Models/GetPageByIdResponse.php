<?php

namespace Kavicms\KavicmsLaravel\Models;

class GetPageByIdResponse
{
    public int $id;
    public string $resourceKey;
    public string $name;
    public string $templateId;
    public Language $language;
    public object $content;
    public array $links;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if ($key === 'language') {
                $this->language = new Language($value);
            } elseif ($key === 'content') {
                $contents = [];
                foreach ($value['content'] as $content) {
                    $contents[$content['resourceKey']] = array_map(
                        fn($raw) => new Content($raw),
                        $content['data'],
                    );
                    usort($contents[$content['resourceKey']], fn($a, $b) => $a->orderNumber < $b->orderNumber);
                }
                $this->content = (object)["content" => $contents];
            } else {
                $this->$key = $value;
            }
        }
    }

    public function getContent(string $key): array|Content
    {
        if (array_key_exists($key, $this->content->content)) {
            $content = $this->content->content[$key];
            return count($content) === 1 ? $content[0] : $content;
        } else {
            throw new \Exception("Unknown content key. Here are the possible keys: " .
                json_encode(array_keys($this->content->content), true));
        }
    }
}
