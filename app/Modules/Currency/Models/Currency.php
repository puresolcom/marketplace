<?php

namespace Awok\Modules\Currency\Models;

use Awok\Core\Eloquent\Model;
use Awok\Modules\Product\Models\Product;

class Currency extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $dates = ['deleted_at'];

    public function products()
    {
        return $this->hasMany(Product::class, 'currency_id');
    }
}