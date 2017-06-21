<?php

namespace Awok\Modules\User\Models;

use Awok\Core\Eloquent\Model;

class UserRole extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public $ownerKey = 'user_id';
}