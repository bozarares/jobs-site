<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Company extends Model
{
    use HasFactory, HasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(25);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    protected $guarded = ['name', 'code'];

    protected $fillable = [
        'name',
        'code',
        'logo',
        'logo_extension',
        'description',
        'country',
        'state',
        'town',
        'address',
        'owner',
        'phone_number',
        'email',
    ];

    protected $with = ['jobs'];
    public function headRecruiter()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
