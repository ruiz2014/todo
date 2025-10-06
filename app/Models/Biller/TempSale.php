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

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function attention()
    {
        return $this->belongsTo(Attention::class, 'code', 'document_code');
    }
}
