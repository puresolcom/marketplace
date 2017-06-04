<?php
namespace Awok\Modules\User\Providers;

use Awok\Modules\User\Services\UserService;

class ModuleServiceProvider extends \Awok\Providers\ModuleServiceProvider
{
    static $routesPaths = [
        __DIR__.'/../routes/routes.php',
    ];

    public function getModuleName(): string
    {
        return 'User';
    }

    public function register()
    {
        parent::register();
        $this->app->singleton('user', function () {
            return $this->app->make(UserService::class);
        });
    }
}