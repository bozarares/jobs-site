<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Job extends Model
{
    use HasFactory, HasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function ($model) {
                return $model->company->name . '-' . $model->title;
            })
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(25);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    protected $fillable = [
        'title',
        'job_type_id',
        'location',
        'salary',
        'description',
    ];

    protected $hidden = ['id', 'created_at', 'updated_at', 'job_type_id'];
    protected $with = ['type', 'skills'];
    protected $appends = ['levels'];

    public function type()
    {
        return $this->belongsTo(JobType::class, 'job_type_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function jobLevels()
    {
        return $this->belongsToMany(
            JobLevel::class,
            'job_level_job',
            'job_id',
            'job_level_id',
            'id'
        )
            ->withTimestamps()
            ->orderBy('job_level_id', 'asc');
    }

    public function getLevelsAttribute()
    {
        return $this->jobLevels()
            ->pluck('level')
            ->toArray();
    }

    public function skills()
    {
        return $this->belongsToMany(
            Skill::class,
            'job_skill',
            'job_id',
            'skill_id',
            'id'
        )->withTimestamps();
    }
}
