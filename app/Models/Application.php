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
            if ($application->isDirty('seen_at')) {
                Notification::create([
                    'user_id' => $application->user_id,
                    'message' =>
                        'Application for job ' .
                        $application->job->title .
                        ' was seen by emaployer.',
                ]);
            }
            if ($application->isDirty('status')) {
                Notification::create([
                    'user_id' => $application->user_id,
                    'message' =>
                        'Application status changed to ' .
                        $application->status .
                        ' for job ' .
                        $application->job->title .
                        '.',
                ]);
                if ($application->status === 'hired') {
                    $application->files()->detach();
                }
                if ($application->status === 'closed') {
                    $application->files()->detach();
                }
            }
        });
    }

    protected $fillable = ['status', 'message', 'job_id', 'user_id', 'seen_at'];
    protected $casts = ['status' => 'string', 'seen_at' => 'datetime'];
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
