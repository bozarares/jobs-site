<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDescription extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'locale'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
