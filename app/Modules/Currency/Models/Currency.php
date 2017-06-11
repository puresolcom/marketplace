<?php

namespace Awok\Modules\Currency\Models;

use Awok\Core\Eloquent\Model;

class Currency extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];
}