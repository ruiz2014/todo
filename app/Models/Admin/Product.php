<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $perPage = 10;
    
    protected $fillable = ['user_id', 'name', 'description', 'product_type', 'company_id', 'price', 'category_id', 'provider_id', 'approved', 'stock', 'minimo'];
}
