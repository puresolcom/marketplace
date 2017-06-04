<?php
namespace Awok\Modules\Product\Providers;

use Awok\Modules\Product\Services\ProductService;

class ModuleServiceProvider extends \Awok\Providers\ModuleServiceProvider
{
    static $routesPaths = [
        __DIR__.'/../routes/routes.php',
    ];

    public function getModuleName(): string
    {
        return 'Product';
    }

    public function register()
    {
        parent::register();
        $this->app->singleton('product', function () {
            return $this->app->make(ProductService::class);
        });
    }
}