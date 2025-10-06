<?php

namespace App\Models\Biller;

use Illuminate\Database\Eloquent\Model;

class Attention extends Model
{
    protected $perPage = 10;
    protected $guarded = [];

    // protected function casts(): array
    // {
    //     return [
    //         'total' => 'decimal:2',
    //     ];
    // }

    public function customer()
    {
        return $this->hasOne('App\Models\Admin\Staff\Customer', 'id', 'customer_id');
    }
    
    public function voucher()
    {
        return $this->hasOne(Voucher::class, 'sunat_code', 'sunat_code');
    }

    // public function tempsale()
    // {
    //     return $this->belonTo('App\Models\Biller\TempSale', 'id', 'customer_id');
    // }
}
