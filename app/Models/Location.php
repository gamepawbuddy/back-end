<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Park;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations'; // Specify the table name if it's different from the default naming convention.

    protected $fillable = ['name', 'latitude', 'longitude']; // Define the fields that are fillable.

    // Define the one-to-many relationship with Park model
     public function parks()
     {
         return $this->hasMany(Park::class);
     }

}