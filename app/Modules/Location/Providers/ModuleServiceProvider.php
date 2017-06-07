<?php
namespace Awok\Modules\Location\Providers;

use Awok\Modules\Location\Services\LocationService;

class ModuleServiceProvider extends \Awok\Providers\ModuleServiceProvider
{
    static $routesPaths = [
        __DIR__.'/../routes/routes.php',
    ];

    public function getModuleName(): string
    {
        return 'Location';
    }

    public function register()
    {
        parent::register();
        $this->app->singleton('Location', function () {
            return $this->app->make(LocationService::class);
        });
    }
}