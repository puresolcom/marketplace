<?php

namespace Awok\Modules\Product\Models;

use Awok\Core\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'products_attributes_values', 'product_id', 'attribute_id')->groupBy('id');
    }
}