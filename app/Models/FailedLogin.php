<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FailedLogin
 *
 * Represents a record of a failed login attempt in the application.
 *
 * @property int $id
 * @property int $user_id
 * @property string $ip_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User $user The user associated with the failed login attempt.
 *
 * @mixin \Eloquent
 */
class FailedLogin extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'ip_address'];

    /**
     * Get the user associated with the failed login.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Override the setUpdatedAt method to prevent setting the updated_at column
     *
     * @param mixed $value
     * @return $this
     */
    public function setUpdatedAt($value) 
    {
        return $this;
    }
}