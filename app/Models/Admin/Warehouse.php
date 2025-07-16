<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;
    protected $perPage = 20;
    protected $fillable = ['user_id', 'warehouse_name', 'phone', 'address', 'company_id'];
}
