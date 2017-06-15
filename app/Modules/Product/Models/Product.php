<?php

namespace Awok\Modules\Product\Models;

use Awok\Core\Eloquent\Model;
use Awok\Modules\Taxonomy\Models\Taxonomy;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $dates = ['deleted_at'];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'products_attributes_values', 'product_id', 'attribute_id')
            ->groupBy('products_attributes.id')
            ->withTimestamps();
    }

    public function attributesValues()
    {
        return $this->hasMany(AttributeValue::class, 'product_id');
    }

    public function categories()
    {
        return $this->taxonomies()->where('type', '=', 'category');
    }

    public function taxonomies()
    {
        return $this->belongsToMany(Taxonomy::class, 'products_taxonomies', 'product_id', 'taxonomy_id')->withTimestamps();
    }

    public function tags()
    {
        return $this->taxonomies()->where('type', '=', 'tag');
    }

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class, 'translatable_id');
    }
}