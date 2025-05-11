<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver extends BaseObserver
{
    protected function getCacheKey(): string
    {
        return 'categories';
    }
}
