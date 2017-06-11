<?php

namespace Awok\Modules\Product\Models;

use Awok\Core\Eloquent\Model;

class ProductTranslation extends Model
{
    public $table = 'products_translations';

    public $timestamps = false;

    protected $guarded = [];
}