<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'company_id', 'establishment_id'];

    protected function scopeRoles($query)
    {
        return $query->where('id', '!=', 1);
    }
}
