<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * Get the employees that belong to this department.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_id');
    }
}