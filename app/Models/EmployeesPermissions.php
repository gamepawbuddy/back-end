<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employeesPermissions extends Model
{
    use HasFactory;

        /**
     * Get the employees that belong to this department.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'permission_id');
    }
}