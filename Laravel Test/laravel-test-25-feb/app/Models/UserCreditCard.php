<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCreditCard extends Model
{
    /**
     * Define Table Name
     */
    protected $table = 'user_credit_card';

    /**
     * Define Primary Key
     */
    protected $primaryKey = 'user_credit_card_id';

    /**
     * Define timestamps
     */

    public $fillable = [
        'creditcard_type',
        'creditcard_number',
        'creditcard_name',
        'creditcard_expired',
        'creditcard_ccv',
        'user_id'
    ];

    /**
     * Define timestamps
     */

    public $timestamps = true;
}
