<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'cuisine',
        'rating',
        'price',
        'url',
        'image_url',
        'latitude',
        'longitude',
    ];

    /**
     * The users that favorited the restaurant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoredBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'restaurant_id', 'user_id');
    }

    /**
     * Get all comments associated with the restaurant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get all reports associated with the restaurant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class)->orderBy('created_at', 'desc');
    }
}
