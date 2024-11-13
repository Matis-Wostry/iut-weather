<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    // Allow mass assignment for these fields
    protected $fillable = ['name', 'country'];

    /**
     * Define the many-to-many relationship between City and User through the pivot table user_cities.
     * Each city can belong to multiple users.
     */
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_cities')
                    ->withPivot('is_favorite')
                    ->withTimestamps();
    }
}

