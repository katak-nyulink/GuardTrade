<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('components.layout.sidebar-item', function ($view) {
            $view->with('isActive', function ($item) {
                if (isset($item['url'])) {
                    return request()->url() === $item['url'];
                }

                if (isset($item['subitems'])) {
                    foreach ($item['subitems'] as $subitem) {
                        if ($this->isActive($subitem)) {
                            return true;
                        }
                    }
                }

                return false;
            });
        });
    }
}
