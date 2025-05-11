<?php

namespace App\Models;

use App\Observers\MenuObserver;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'title',
        'route',
        'icon',
        'parent_id',
        'permission_name',
        'order',
        'group',
        'type',
        'is_active'
    ];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function getFullPathAttribute()
    {
        $path = [];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->title);
            $parent = $parent->parent;
        }

        return implode(' > ', $path) . ' > ' . $this->title;
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::observe(MenuObserver::class);
    }
}
