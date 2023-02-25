<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * Define Table Name
     */
    protected $table = 'user';

    /**
     * Define Primary Key
     */
    protected $primaryKey = 'user_id';

    /**
     * Define timestamps
     */

    public $fillable = [
        'name',
        'address',
        'email',
        'password',
        'creditcard_type',
        'photos',
        'creditcard_number',
        'creditcard_name',
        'creditcard_expired',
        'creditcard_ccv',
    ];

    /**
     * Define timestamps
     */

    public $timestamps = false;
}
