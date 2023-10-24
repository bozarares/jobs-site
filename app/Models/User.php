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
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'tag',
        'description',
        'avatar',
        'social_linkedin',
        'social_github',
        'social_facebook',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token', 'isOwner'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $with = ['jobHistory', 'educationHistory'];

    public function companies()
    {
        return $this->hasMany(Company::class, 'owner');
    }

    public function getIsOwnerAttribute()
    {
        return $this->companies()->count() > 0;
    }

    public function jobHistory()
    {
        return $this->hasMany(JobHistory::class);
    }

    public function educationHistory()
    {
        return $this->hasMany(EducationHistory::class);
    }
}
