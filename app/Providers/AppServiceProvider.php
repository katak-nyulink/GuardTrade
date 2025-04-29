<?php

namespace App\Providers;

// use Illuminate\Contracts\View\View;

// use Illuminate\Support\Facades\View;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/menu.php',
            'menu'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::automaticallyEagerLoadRelationships();
        // View::share( 'menuItems', [
        //     [
        //         'label' => 'Tables',
        //         'icon' => 'heroicon-o-table-cells',
        //         'url' => '#'
        //     ],
        //     [
        //         'label' => 'Pages',
        //         'icon' => 'heroicon-o-document-text',
        //         'subitems' => [
        //             [
        //                 'label' => 'Generic',
        //                 'url' => '#'
        //             ],
        //             [
        //                 'label' => 'Authentication',
        //                 'url' => '#'
        //             ],
        //             [
        //                 'label' => 'Profile',
        //                 'url' => '#'
        //             ],
        //             [
        //                 'label' => 'Invoices',
        //                 'url' => '#'
        //             ],
        //             [
        //                 'label' => 'Errors',
        //                 'icon' => 'heroicon-o-exclamation-triangle',
        //                 'subitems' => [
        //                     [
        //                         'label' => '404',
        //                         'subitems' => [
        //                             ['label' => 'Basic', 'url' => '#'],
        //                             ['label' => 'Illustration', 'url' => '#'],
        //                             ['label' => 'Illustration Cover', 'url' => '#']
        //                         ]
        //                     ],
        //                     [
        //                         'label' => '500',
        //                         'subitems' => [
        //                             ['label' => 'Basic', 'url' => '#'],
        //                             ['label' => 'Illustration', 'url' => '#'],
        //                             ['label' => 'Illustration Cover', 'url' => '#']
        //                         ]
        //                     ],
        //                     [
        //                         'label' => 'Maintenance',
        //                         'subitems' => [
        //                             ['label' => 'FAQ', 'url' => '#']
        //                         ]
        //                     ]
        //                 ]
        //             ]
        //         ]
        //     ]
        // ]);
    }
}
