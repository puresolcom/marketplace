<?php

namespace Awok\Modules\Product\Models;

use Awok\Core\Eloquent\Model;

class AttributeOption extends Model
{
    protected $table = 'products_attributes_options';

    protected $guarded = [];

    protected $hidden = ['attribute_id', 'created_at', 'updated_at'];

    public function translations()
    {
        return $this->hasMany(AttributeOptionTranslation::class, 'translatable_id');
    }
}