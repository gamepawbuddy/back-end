<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\UserActivity;
use App\Models\Subscription;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str; // Import the Str facade for UUID generation

class Users extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified' => 'boolean',
        'phone_verified' => 'boolean',
        'favorite_dog_breeds' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate a UUID when creating a new user
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Get all the activities performed by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activitiesPerformed()
    {
        return $this->morphMany(UserActivity::class, 'performed_by');
    }

    /**
     * A user can have one subscription.
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    /**
     * Determines if the user has a premium subscription.
     *
     * This method checks if the user's subscription ID matches the value for a premium subscription.
     * In this implementation, a subscription_id of 2 is considered as premium.
     *
     * @return bool Returns true if the user has a premium subscription, otherwise returns false.
     */
    public function isPremium()
    {
        // If the user's subscription_id is 2, they are considered premium
        if ($this->subscription_id === 2) {
            return true;
        }

        // Otherwise, they are not premium
        return false;
    }

}