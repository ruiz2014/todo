<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Local extends Model
{
    use SoftDeletes;
    protected $perPage = 20;
    protected $fillable = ['user_id', 'company_id', 'local_name', 'phone', 'address'];
}
