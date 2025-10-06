<?php

namespace App\Models\Biller;

use Illuminate\Database\Eloquent\Model;

class CreditLog extends Model
{
    protected $perPage = 10;
    protected $guarded = [];

    public function credit()
    {
        return $this->belongsTo(Attention::class, 'attention_id', 'id');
    }

}
