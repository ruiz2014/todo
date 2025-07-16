<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class WarehouseProduct extends Model
{
    protected $fillable = ['user_id', 'warehouse_id', 'product_id', 'stock', 'company_id'];
}
