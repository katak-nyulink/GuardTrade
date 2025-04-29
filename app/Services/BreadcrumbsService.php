<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;

class BreadcrumbsService
{
    protected array $breadcrumbs = [];

    public function add(string $title, $url = null): self
    {
        $this->breadcrumbs[] = [
            'title' => $title,
            'url' => $url,
            'active' => $url === null
        ];

        return $this;
    }

    public function generate(): array
    {
        return $this->breadcrumbs;
    }
}
