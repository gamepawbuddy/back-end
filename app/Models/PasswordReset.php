<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * PasswordReset Model
 *
 * Represents a password reset record in the database. This model is used
 * to interact with the 'password_resets' table which stores the email,
 * hashed token, and creation timestamp for password reset requests.
 */
class PasswordReset extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'password_resets';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',       
        'token',      
        'created_at', 
    ];
}