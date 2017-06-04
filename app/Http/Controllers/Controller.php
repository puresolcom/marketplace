<?php

namespace Awok\Http\Controllers;

use Awok\Core\Support\RestfulResponseTrait;
use Awok\Core\Support\RestfulValidateTrait;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use RestfulResponseTrait, RestfulValidateTrait;
}
