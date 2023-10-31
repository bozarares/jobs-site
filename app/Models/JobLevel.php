<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobLevel extends Model
{
    use HasFactory;

    protected $fillable = ['level'];

    protected $hidden = ['id', 'created_at', 'updated_at', 'pivot'];
    public function jobs()
    {
        return $this->belongsToMany(
            Job::class,
            'job_level_job',
            'job_level_id',
            'job_id',
            'id'
        )->withTimestamps();
    }
}
