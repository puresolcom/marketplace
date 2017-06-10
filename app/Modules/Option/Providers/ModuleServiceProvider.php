<?php

namespace Awok\Modules\Option\Providers;

use Awok\Modules\Option\Services\OptionService;

class ModuleServiceProvider extends \Awok\Providers\ModuleServiceProvider
{
    public function getModuleName(): string
    {
        return 'Option';
    }

    public function register()
    {
        parent::register();
        $this->app->singleton('option', function () {
            return $this->app->make(OptionService::class);
        });
    }
}