<?php

namespace Awok\Modules\Product\Models;

use Awok\Core\Eloquent\Model;

class AttributeValue extends Model
{
    public $timestamps = true;

    protected $table = 'products_attributes_values';

    protected $guarded = [];

    protected $hidden = ['product_id', 'attribute_id', 'created_at', 'updated_at'];

    public function translations()
    {
        return $this->hasMany(AttributeValueTranslation::class, 'translatable_id');
    }
}