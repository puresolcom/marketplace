<?php

namespace Awok\Modules\Product\Models;

use Awok\Core\Eloquent\Model;

class AttributeValue extends Model
{
    protected $table = 'products_attributes_values';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];
}