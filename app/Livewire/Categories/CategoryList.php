<?php

namespace App\Livewire\Categories;

use App\Collections\TableColumns;
use App\Models\Category;
use App\Utils\PageComponent;
use Developermithu\Tallcraftui\Traits\WithTcTable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

class CategoryList extends PageComponent
{
    use WithTcTable;

    public Collection $columns;

    protected $cacheKey = 'categories';
    protected $cacheDuration = 86400; // 24 hours
    protected string $pathName = 'categories';

    // public Category $category;

    public function mount()
    {
        // Cache::forget($this->cacheKey);
        $this->columns = TableColumns::category();
    }

    #[Computed]
    public function categories()
    {
        return Category::query()
            ->where('parent_id', null)
            // ->withCount(['children'])
            ->with(['children.children'])
            ->withCount('children')
            ->when($this->tcSearch, function ($query) {
                $query->where('name', 'LIKE', '%' . $this->tcSearch . '%')
                    ->orWhere('slug', 'LIKE', '%' . $this->tcSearch . '%')
                    ->orWhere('description', 'LIKE', '%' . $this->tcSearch . '%');
            })
            ->tap(fn($query) => $this->tcApplySorting($query))
            // ->withRelationshipAutoloading()
            ->simplePaginate(10);

        // $this->categories = $categories->withRelationshipAutoloading();
    }
}
