<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    const ADMIN_ROLE_ID = 1;
    const USER_ROLE_ID = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
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
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    # Use this method to get all the post of the user
    public function posts(){
        return $this->hasMany(Post::class)->latest();
    }

    # Use this method to get all the followers of a user
    public function followers(){
        return $this->hasMany(Follow::class, 'following_id');
    }

    # Use this method to get all the users that user is following
    public function following(){
        return $this->hasMany(Follow::class, 'follower_id');
    }

    # Method to check if the user is already followed
    public function isFollowed(){
        return $this->followers()->where('follower_id', Auth::user()->id)->exists();
    }
}
