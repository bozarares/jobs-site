<?php

namespace App\Models;

use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    use PivotEventTrait;

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

    public function jobs()
    {
        return $this->belongsToMany(Skill::class, 'job_skill');
    }
}
