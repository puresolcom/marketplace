<?php

namespace Awok\Modules\Taxonomy\Models;

use Awok\Core\Eloquent\Model;

class TaxonomyTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'taxonomies_translations';

    protected $guarded = [];
}