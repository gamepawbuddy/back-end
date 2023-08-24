<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dog extends Model
{
    use HasFactory;

    /**
     * The breeds associated with the dog.
     */
    public function breeds()
    {
        // Using the 'breed_dog' pivot table for the relationship
        return $this->belongsToMany(Breed::class, 'breed_dog');
    }
}