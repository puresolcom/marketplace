<?php

namespace Awok\Modules\Store\Models;

use Awok\Core\Eloquent\Model;
use Awok\Modules\Location\Models\Country;
use Awok\Modules\Location\Models\Location;
use Awok\Modules\User\Models\User;

class Store extends Model
{
    protected $guarded = [];

    public $ownerKey = 'user_id';

    public function city()
    {
        return $this->belongsTo(Location::class, 'city_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}