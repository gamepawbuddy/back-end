<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserActivity; 

/**
 * App\Models\Users
 *
 * Represents a user in the application.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserActivity[] $activitiesPerformed
 * @property-read int|null $activities_performed_count
 *
 * @mixin \Eloquent
 */
class Users extends Model
{
    use HasFactory;

    /**
     * Get all the activities performed by the user.
     *
     * This relationship is polymorphic, meaning that this user can be associated
     * with activities in the `user_activity` table through the `performed_by` relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activitiesPerformed()
    {
        return $this->morphMany(UserActivity::class, 'performed_by');
    }
}