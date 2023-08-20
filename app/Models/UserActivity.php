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
     * The table associated with the model.
     *
     * This property specifies the database table that the UserActivity model
     * corresponds to.
     *
     * @var string
     */
    protected $table = 'user_activity';

    /**
     * Indicates if the model should be timestamped.
     *
     * This property determines whether the UserActivity model should
     * automatically manage the "created_at" and "updated_at" timestamps.
     *
     * @var bool
     */
    public $timestamps = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =[
        'user_id',
        'ip_address',
        'activity_type',
        'activity_details',
        'performed_by_id',
        'performed_by_type'
    ];

    
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