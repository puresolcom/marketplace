<?php

namespace Awok\Modules\Product\Models;

use Awok\Core\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'products_attributes';

    protected $guarded = [];

    protected $hidden = ['pivot', 'configuration', 'created_at', 'updated_at'];

    public static function findBySlug($slug)
    {
        return self::where('slug', '=', $slug)->first();
    }

    public function options()
    {
        return $this->hasMany(AttributeOption::class, 'attribute_id', 'id');
    }

    public function translations()
    {
        return $this->hasMany(AttributeTranslation::class, 'translatable_id', 'id');
    }
}