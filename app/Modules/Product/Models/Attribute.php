<?php

namespace Awok\Modules\Product\Models;

use Awok\Core\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'products_attributes';

    protected $guarded = [];

    protected $hidden = ['pivot', 'configuration', 'created_at', 'updated_at'];

    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id', 'id');
    }

    public function options()
    {
        return $this->hasMany(AttributeOption::class, 'attribute_id', 'id');
    }
}