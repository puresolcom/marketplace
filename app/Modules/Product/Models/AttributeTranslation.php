<?php

namespace Awok\Modules\Product\Models;

use Awok\Core\Eloquent\Model;

class AttributeTranslation extends Model
{
    protected $table = 'products_attributes_translations';

    protected $guarded = [];

    protected $hidden = ['id', 'translatable_id'];
}