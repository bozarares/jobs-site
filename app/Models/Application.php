<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::updated(function ($application) {
            if ($application->isDirty('status')) {
                $application->files()->detach();
            }
        });
    }

    protected $fillable = ['status', 'message'];
    protected $casts = ['status' => 'string'];
    protected $with = ['user', 'job', 'files'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function files()
    {
        return $this->belongsToMany(
            UserFile::class,
            'application_files',
            'application_id',
            'user_file_id',
            'id'
        )->withTimestamps();
    }
}
