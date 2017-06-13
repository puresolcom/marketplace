<?php

namespace Awok\Modules\Location\Models;

use Awok\Core\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $dates = ['deleted_at'];
}