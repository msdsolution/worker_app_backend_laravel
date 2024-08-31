<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Storage;
use App\Models\UserDocuments;
use App\Models\worker_feedback;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $userId;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'location',
        'user_type',
        'status',
        'city_id',
        'phone_no',
        'user_address',
        'pro_pic_url',
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

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomVerifyEmailQueued);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function workerFeedback()
    {
        return $this->hasMany(worker_feedback::class, 'user_id', 'id');
    }

    // Accessor for pro_pic_url
    public function getProPicUrlAttribute()
    {
        return $this->attributes['pro_pic_url'] ? url('storage/' . $this->attributes['pro_pic_url']) : null;
    }

    public function userDocs()
    {
        return $this->hasMany(UserDocuments::class, 'user_id');
    }

}
