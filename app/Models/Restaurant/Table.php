<?php

namespace App\Models\Restaurant;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $guarded = [];
    
    public function temp_restaurant()
    {
        return $this->hasMany('App\Models\Biller\TempRestaurant', 'table_id', 'id');
    }

    public function room()
    {
        return $this->hasOne('App\Models\Restaurant\Room', 'id', 'room_id');
    }
}
