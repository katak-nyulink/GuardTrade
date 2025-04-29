<?php

namespace App\Livewire;

use App\Services\BreadcrumbsService;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class Breadcrumbs extends Component
{
    public array $breadcrumbs = [];

    public function mount(BreadcrumbsService $breadcrumbsService)
    {
        // $this->breadcrumbs = $breadcrumbsService->generateBreadcrumbs();
        $this->generateBreadcrumbs();
    }

    public function generateBreadcrumbs()
    {
        $routeName = Route::currentRouteName();
        $segments = explode('.', $routeName);

        $url = '';
        foreach ($segments as $i => $segment) {
            $url .= ($i === 0 ? '' : '.') . $segment;
            $this->breadcrumbs[] = [
                'name' => ucfirst(str_replace('-', ' ', $segment)),
                'route' => route($url, [], false),
                'active' => $i === array_key_last($segments)
            ];
        }
    }

    public function render()
    {
        return view('livewire.breadcrumbs');
    }
}
