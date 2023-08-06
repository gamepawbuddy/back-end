<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * Get the department that the employee belongs to.
     */
    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'department_id');
    }

    /**
     * Get the permission that the employee has.
     */
    public function permission()
    {
        return $this->belongsTo('App\Models\Permission', 'permission_id');
    }
}