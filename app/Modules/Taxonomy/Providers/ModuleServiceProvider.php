<?php
namespace Awok\Modules\Taxonomy\Providers;

use Awok\Modules\Taxonomy\Services\TaxonomyService;

class ModuleServiceProvider extends \Awok\Providers\ModuleServiceProvider
{
    static $routesPaths = [
        __DIR__.'/../routes/routes.php',
    ];

    public function getModuleName(): string
    {
        return 'Taxonomy';
    }

    public function register()
    {
        parent::register();
        $this->app->singleton('taxonomy', function () {
            return $this->app->make(TaxonomyService::class);
        });
    }
}