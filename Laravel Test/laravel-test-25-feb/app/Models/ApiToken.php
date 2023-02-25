<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    /**
     * Define Table Name
    */
    protected $table = 'api_token';

    /**
     * Define Primary Key
    */
    protected $primaryKey = 'api_token_id';

    /**
     * Define timestamps
    */

    public $timestamps = false;

}
