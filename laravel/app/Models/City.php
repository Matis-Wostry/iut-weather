<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    // Allow mass assignment for these fields
    protected $fillable = ['name', 'country'];

    /**
     * Define the many-to-many relationship between City and User through the pivot table `user_cities`.
     *
     * Each city can be associated with multiple users, and users can have multiple favorite cities.
     * The pivot table `user_cities` stores additional attributes such as `is_favorite`.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany The relationship between cities and users.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_cities')
                    ->withPivot('is_favorite')
                    ->withTimestamps();
    }
}

