<?php

namespace Awok\Modules\Taxonomy\Models;

use Awok\Core\Eloquent\Model;
use Awok\Modules\Product\Models\Product;

class Taxonomy extends Model
{
    public $timestamps = true;

    public $hidden = ['pivot', 'created_at', 'updated_at'];

    protected $guarded = [];

    public function translations()
    {
        return $this->hasMany(TaxonomyTranslation::class, 'translatable_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_taxonomies', 'taxonomy_id', 'product_id')->withTimestamps();
    }
}