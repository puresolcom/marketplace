<?php
namespace Awok\Modules\Currency\Providers;

use Awok\Modules\Currency\Services\CurrencyService;

class ModuleServiceProvider extends \Awok\Providers\ModuleServiceProvider
{
    static $routesPaths = [
        __DIR__.'/../routes/routes.php',
    ];

    public function getModuleName(): string
    {
        return 'Currency';
    }

    public function register()
    {
        parent::register();
        $this->app->singleton('currency', function () {
            return $this->app->make(CurrencyService::class);
        });
    }
}