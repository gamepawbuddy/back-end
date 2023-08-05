<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserActivity
 *
 * Represents an activity performed by or on a user in the application.
 *
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $performedBy
 *
 * @mixin \Eloquent
 */
class UserActivity extends Model
{
    use HasFactory;

    /**
     * Get the entity (User, Employee, etc.) that performed the activity.
     *
     * This relationship is polymorphic, allowing for flexibility in associating
     * this activity with multiple types of entities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function performedBy()
    {
        return $this->morphTo();
    }
}