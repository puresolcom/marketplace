<?php

namespace Awok\Providers;

use Awok\Core\Events\RestBuilderQueryReady;
use Awok\Core\Listeners\RestBuilderRestrictAccess;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        RestBuilderQueryReady::class => [
            RestBuilderRestrictAccess::class,
        ],
    ];
}
