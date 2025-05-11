<?php

namespace App\Providers;

// use Illuminate\Contracts\View\View;

// use Illuminate\Support\Facades\View;

use App\Models\{Menu, Setting, Category};
use App\Observers\{MenuObserver, SettingObserver, CategoryObserver};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Number;
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

        $this->app->singleton('menu', function () {
            return new \App\Services\MenuService();
        });
        $this->app->singleton('setting', function () {
            return new \App\Services\SettingService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Menu::observe(MenuObserver::class);
        Setting::observe(SettingObserver::class);
        Category::observe(CategoryObserver::class);

        // Model::automaticallyEagerLoadRelationships();

        Number::useLocale(config('app.locale'));

        // Set default currency with fallback to USD
        $currency = app('setting')->get('default_currency_code', 'USD');
        Number::useCurrency($currency);

        // Lang::stringable(function (Money $money) {
        //     return $money->formatTo('en_GB');
        // });

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
