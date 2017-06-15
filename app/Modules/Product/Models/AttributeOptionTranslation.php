<?php

namespace Awok\Modules\Product\Models;

use Awok\Core\Eloquent\Model;

class AttributeOptionTranslation extends Model
{
    protected $table = 'products_attributes_options_translations';

    protected $guarded = [];

    protected $hidden = ['id', 'translatable_id'];

    public $timestamps = false;
}