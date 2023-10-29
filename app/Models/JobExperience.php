<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobExperience extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $hidden = ['id', 'created_at', 'updated_at', 'pivot'];
    public function jobs()
    {
        return $this->belongsToMany(
            Job::class,
            'job_experience_job',
            'job_experience_id',
            'job_id',
            'id'
        )->withTimestamps();
    }
}
