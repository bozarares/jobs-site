<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($skill) {
            $skill->users()->detach();
        });
    }

    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skill');
    }
}
