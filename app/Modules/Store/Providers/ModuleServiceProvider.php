<?php
namespace Awok\Modules\Store\Providers;

use Awok\Modules\Store\Services\StoreService;

class ModuleServiceProvider extends \Awok\Providers\ModuleServiceProvider
{
    static $routesPaths = [
        __DIR__.'/../routes/routes.php',
    ];

    public function getModuleName(): string
    {
        return 'Store';
    }

    public function register()
    {
        parent::register();
        $this->app->singleton('store', function () {
            return $this->app->make(StoreService::class);
        });
    }
}