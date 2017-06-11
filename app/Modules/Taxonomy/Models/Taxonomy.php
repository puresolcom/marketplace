<?php

namespace Awok\Modules\Taxonomy\Models;

use Awok\Core\Eloquent\Model;

class Taxonomy extends Model
{
    public $timestamps = true;

    public $hidden = ['pivot', 'created_at', 'updated_at'];

    protected $guarded = [];

    public function translations()
    {
        return $this->hasMany(TaxonomyTranslation::class, 'translatable_id', 'id');
    }
}