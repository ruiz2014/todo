<?php

namespace App\Models\Biller;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $perPage = 10;
    protected $guarded = [];

    public function customer()
    {
        return $this->hasOne('App\Models\Admin\Staff\Customer', 'id', 'customer_id');
    }
}
