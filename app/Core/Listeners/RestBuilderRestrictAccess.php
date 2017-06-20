<?php

namespace Awok\Core\Listeners;

class RestBuilderRestrictAccess
{
    public function handle($event)
    {
        $builderInstance = $event->builderInstance;
        $model           = $event->model;

        return $model->filterByOwner($builderInstance);
    }
}