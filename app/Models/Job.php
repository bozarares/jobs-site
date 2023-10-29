<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'job_type_id',
        'location',
        'salary',
        'description',
    ];

    protected $hidden = ['id', 'created_at', 'updated_at', 'job_type_id'];
    protected $with = ['type'];
    protected $appends = ['experience_names'];

    public function type()
    {
        return $this->belongsTo(JobType::class, 'job_type_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function jobExperiences()
    {
        return $this->belongsToMany(
            JobExperience::class,
            'job_experience_job',
            'job_id',
            'job_experience_id',
            'id'
        )->withTimestamps();
    }

    public function getExperienceNamesAttribute()
    {
        return $this->jobExperiences()
            ->pluck('name')
            ->toArray();
    }
}
