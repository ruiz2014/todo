<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Establishment
 *
 * @property $id
 * @property $company_id
 * @property $user_id
 * @property $name
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Establishment extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['company_id', 'user_id', 'name'];


}
