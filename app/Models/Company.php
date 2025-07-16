<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Company
 *
 * @property $id
 * @property $name
 * @property $company name
 * @property $document
 * @property $address
 * @property $ubigeo
 * @property $sector_id
 * @property $number_employees
 * @property $number_subsidiary
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Company extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'company name', 'document', 'address', 'ubigeo', 'sector_id', 'number_employees', 'number_subsidiary'];


}
