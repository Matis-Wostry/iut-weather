<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * These fields can be modified using mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'wants_email',
        'forecast_scope'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * These fields will not be included in JSON responses when the user model is returned.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * This ensures that certain attributes are converted to appropriate data types.
     *
     * @return array<string, string> The attributes and their corresponding data types.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Define the many-to-many relationship between Users and Cities through the pivot table `user_cities`.
     *
     * This allows users to have multiple favorite cities, and stores additional information
     * such as whether a city is marked as a favorite (`is_favorite`).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany The relationship between users and their favorite cities.
     */
    public function favoriteCities() {
        return $this->belongsToMany(City::class, 'user_cities')
                    ->withPivot('is_favorite')
                    ->withTimestamps();
    }
}
