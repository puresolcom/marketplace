<?php

namespace Awok\Modules\Taxonomy\Models;

use Awok\Core\Eloquent\Model;

class TaxonomyTranslation extends Model
{
    protected $table = 'taxonomies_translations';

    protected $guarded = [];

    public $timestamps = false;
}