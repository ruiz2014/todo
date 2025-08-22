<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class BuyProduct extends Model
{
    protected $perPage = 10;
    protected $guarded = [];

    public function provider()
    {
        return $this->hasOne('App\Models\Admin\Staff\Provider', 'id', 'provider_id');
    }

    public function establishment()
    {
        return $this->hasOne('App\Models\Admin\Staff\Establishment', 'type', 'location_type');
    }
}
