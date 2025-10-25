<?php

namespace App\Models\Biller;

use Illuminate\Database\Eloquent\Model;

class TempRestaurant extends Model
{
    protected $guarded = [];

    public function table()
    {
        return $this->hasOne('App\Models\Restaurant\Table', 'id', 'table_id');
    }

    public function product()
    {
        return $this->hasOne('App\Models\Admin\Product', 'id', 'product_id');
    }
}
