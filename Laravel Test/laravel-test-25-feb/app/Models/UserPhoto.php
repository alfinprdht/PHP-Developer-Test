<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPhoto extends Model
{
    /**
     * Define Table Name
     */
    protected $table = 'user_photo';

    /**
     * Define Primary Key
     */
    protected $primaryKey = 'user_photo_id';

    /**
     * Define timestamps
     */

    public $fillable = [
        'filename',
        'user_id'
    ];

    /**
     * Define timestamps
     */

    public $timestamps = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */

     public function User()
     {
         return $this->belongsTo(\App\Models\User::class, 'user_id', 'user_id');
     }

}
