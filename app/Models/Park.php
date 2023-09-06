<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Park extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location_id', 'latitude', 'longitude'];

    // Define the inverse of the one-to-many relationship with Location model
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}