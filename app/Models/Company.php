<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'logo',
        'description',
        'location',
        'head_recruiter',
    ];

    public function headRecruiter()
    {
        return $this->belongsTo(User::class, 'head_recruiter');
    }
}
