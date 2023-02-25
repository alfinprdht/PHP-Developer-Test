<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public $timestamps = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function userCreditCard()
    {
        return $this->hasMany(\App\Models\UserCreditCard::class, 'user_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function UserPhoto()
    {
        return $this->hasMany(\App\Models\UserPhoto::class, 'user_id', 'user_id');
    }
    
}
