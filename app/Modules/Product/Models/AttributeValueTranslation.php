<?php

namespace Awok\Modules\Product\Models;

use Awok\Core\Eloquent\Model;

class AttributeValueTranslation extends Model
{
    protected $table = 'products_attributes_values_translations';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];
}