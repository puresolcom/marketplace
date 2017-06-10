<?php

namespace Awok\Modules\Product\Models;

use Awok\Core\Eloquent\Model;
use Awok\Modules\Taxonomy\Models\Taxonomy;

class Product extends Model
{
    protected $guarded = [];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'products_attributes_values', 'product_id', 'attribute_id')
            ->groupBy('products_attributes.id')
            ->withTimestamps();
    }

    public function taxonomies()
    {
        return $this->belongsToMany(Taxonomy::class, 'products_taxonomies', 'product_id', 'taxonomy_id')->withTimestamps();
    }

    public function categories()
    {
        return $this->taxonomies()->where('type', '=', 'category');
    }

    public function tags()
    {
        return $this->taxonomies()->where('type', '=', 'tag');
    }
}