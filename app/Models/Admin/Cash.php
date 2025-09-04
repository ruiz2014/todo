<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    protected $perPage = 10;
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'seller');
    }

    public function local()
    {
        return $this->hasOne('App\Models\Admin\Local', 'id', 'local_cash');
    }
}
