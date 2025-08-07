<?php

namespace App\Models\Biller;

use Illuminate\Database\Eloquent\Model;

class Attention extends Model
{
    protected $guarded = [];

    public function customer()
    {
        return $this->hasOne('App\Models\Admin\Staff\Customer', 'id', 'customer_id');
    }
    
    public function voucher()
    {
        return $this->hasOne(Voucher::class, 'sunat_code', 'sunat_code');
    }
}
