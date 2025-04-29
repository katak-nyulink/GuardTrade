<?php

namespace App\Traits;

use App\Services\BreadcrumbsService;

trait WithBreadcrumbs
{
    protected function breadcrumbs(): BreadcrumbsService
    {
        return app(BreadcrumbsService::class);
        // ->add('Home', route('dashboard'));
    }
}
