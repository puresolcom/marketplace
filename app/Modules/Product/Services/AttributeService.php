<?php

namespace Awok\Modules\Product\Services;

use Awok\Core\Foundation\BaseService;
use Awok\Modules\Product\Models\Attribute;

class AttributeService extends BaseService
{
    public function __construct(Attribute $attribute)
    {
        $this->setBaseModel($attribute);
    }
}