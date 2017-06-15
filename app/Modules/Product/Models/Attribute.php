<?php

namespace Awok\Modules\Product\Models;

use Awok\Core\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'products_attributes';

    protected $guarded = ['values'];

    protected $hidden = ['pivot', 'created_at', 'updated_at'];

    public static function findBySlug($slug)
    {
        return self::where('slug', '=', $slug)->first();
    }

    public function values()
    {
        dd($this);
        return $this->hasMany(AttributeValue::class, 'attribute_id', 'id')->where('attribute_id', '=', 1);
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