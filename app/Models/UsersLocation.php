<?php

namespace App\Models;

use App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UsersLocation extends Model
{
    protected $table = 'users_location';

    protected $fillable = ['user_id', 'latitude', 'longitude', 'status'];

    /**
     * Define a relationship with the User model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}