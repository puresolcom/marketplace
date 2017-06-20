<?php

namespace Awok\Core\Events;

class RestBuilderQueryReady
{
    public $builderInstance;

    public $model;

    public function __construct($builderInstance, $model)
    {
        $this->builderInstance = $builderInstance;
        $this->model           = $model;
    }
}
