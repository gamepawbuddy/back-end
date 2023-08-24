<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dog;

class Breed extends Model
{
    use HasFactory;

    /**
     * The dogs that belong to the breed.
     */
    public function dogs()
    {
        // Using the 'breed_dog' pivot table for the relationship
        return $this->belongsToMany(Dog::class, 'breed_dog');
    }
}