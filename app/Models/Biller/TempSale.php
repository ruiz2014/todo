<?php

namespace App\Models\Biller;

use Illuminate\Database\Eloquent\Model;

class TempSale extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->hasOne('App\Models\Admin\Product', 'id', 'product_id');
    }
}
