<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Customer
 *
 * @property $id
 * @property $company_id
 * @property $local_id
 * @property $user_id
 * @property $name
 * @property $tipo_doc
 * @property $document
 * @property $phone
 * @property $address
 * @property $email
 * @property $ubigeo
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Customer extends Model
{
    use SoftDeletes;

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['company_id', 'local_id', 'user_id', 'name', 'tipo_doc', 'document', 'phone', 'address', 'email', 'ubigeo'];


}
