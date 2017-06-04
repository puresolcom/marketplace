<?php

namespace Awok\Modules\Location\Models;

use Awok\Core\Eloquent\Model;

class Country extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];
}