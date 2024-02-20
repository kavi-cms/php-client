<?php

namespace Kavicms\KavicmsLaravel\Models;

class NavigationGroup
{
    public int $id;
    public ?int $parentId;
    public string $title;
    public ?array $items = []; // will contain inner navigation groups
    public int $navigationGroupId;
    public string $url;
    public int $orderNumber;
    public Language $language;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if ($key === 'language') {
                    $this->language = new Language($value);
                } else {
                    $this->$key = $value;
                }
            }
        }
    }

    // helper function for rearranging navigation groups
    static function findParentAddItem(array &$arr, NavigationGroup $item): bool
    {
        if ($parent = $arr[$item->parentId]) {
            // parent found, update parent to have item as child
            $parent->items[$item->orderNumber] = $item;
            $arr[$item->parentId] = $parent;
            return true;
        } else {
            foreach ($arr as $arrItem) {
                if (!count($arrItem->items)) {
                    // arrItem's children list is empty, cannot be its child
                    continue;
                } else {
                    // ask items children find parent
                    if (self::findParentAddItem($arrItem->items, $item)) {
                        return true;
                    }
                }
            }
        }
        // no parent found
        return false;
    }

    // helper function for reordering navigation groups
    static function sort(array $arr): array
    {
        usort($arr, fn(NavigationGroup $a, NavigationGroup $b) => $a->orderNumber < $b->orderNumber);
        foreach ($arr as $item) {
            if (count($item->items)) {
                $item->items = self::sort($item->items);
            }
        }
        return $arr;
    }
}
