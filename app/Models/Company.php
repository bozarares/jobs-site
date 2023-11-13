<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Mews\Purifier\Facades\Purifier;
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

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($company) {
            $filename = $company->logo . '.' . $company->logo_extension;
            $filePath = storage_path('app/tmp/' . $filename);
            $targetDirectory = storage_path('app/public/logos/companies');
            File::ensureDirectoryExists($targetDirectory, 0755, true);

            if (File::exists($filePath)) {
                File::move($filePath, $targetDirectory . '/' . $filename);
            }
        });

        static::updated(function ($company) {
            if ($company->isDirty('logo')) {
                $filename = $company->logo . '.' . $company->logo_extension;
                $filePath = storage_path('app/tmp/' . $filename);
                $targetDirectory = storage_path('app/public/logos/companies');
                File::ensureDirectoryExists($targetDirectory, 0755, true);

                if (File::exists($filePath)) {
                    File::move($filePath, $targetDirectory . '/' . $filename);
                }

                $current_logo_path =
                    $targetDirectory .
                    '/' .
                    $company->getOriginal('logo') .
                    '.' .
                    $company->getOriginal('logo_extension');
                if (File::exists($current_logo_path)) {
                    File::delete($current_logo_path);
                }
            }
        });
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

    protected $hidden = ['owner', 'code', 'created_at', 'updated_at'];
    public function headRecruiter()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    // public function getApplicationNumberAttribute()
    // {
    //     $application_number = 0;
    //     foreach ($this->jobs as $job) {
    //         foreach ($job->applications as $application) {
    //             if ($application->status == 'open') {
    //                 $application_number++;
    //             }
    //         }
    //     }
    //     return $application_number;
    // }
}
