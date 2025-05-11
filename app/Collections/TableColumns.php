<?php

namespace App\Collections;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TableColumns extends Collection
{
    static function lang(string $root, string $key)
    {
        return __("crud." . Str::plural($root) . ".inputs.{$key}.label");
    }

    public static function category(): self
    {
        $caller = __FUNCTION__ ?? 'category';

        return new static([
            'name' => [
                'label' => self::lang($caller, 'name'),
                'sortable' => true
            ],
            'slug' => [
                'label' => self::lang($caller, 'slug'),
                'sortable' => true
            ],
            'description' => [
                'label' => self::lang($caller, 'description'),
                'sortable' => true
            ],
            // 'parent' => [
            //     'label' => self::lang($caller, 'parent'),
            //     'sortable' => true
            // ],
            'created_at' => [
                'label' => self::lang($caller, 'created_at'),
                'sortable' => true
            ],
            'actions' => [
                'label' => self::lang($caller, 'actions'),
                'sortable' => false
            ],
        ]);
    }
}
