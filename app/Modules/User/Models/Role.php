<?php

namespace Awok\Modules\User\Models;

use Awok\Core\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    protected $hidden = ['pivot'];

    public $timestamps = false;
}