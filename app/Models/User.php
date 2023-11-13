<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\File;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, PivotEventTrait;

    public static function boot()
    {
        parent::boot();

        static::pivotDetached(function ($model, $relationName, $pivotIds) {
            if ($relationName === 'skills') {
                foreach ($pivotIds as $skillId) {
                    $skill = Skill::find($skillId);
                    if ($skill && $skill->users()->count() == 0) {
                        $skill->delete();
                    }
                }
            }
        });

        static::updated(function ($user) {
            if ($user->isDirty('avatar')) {
                $filename = $user->avatar . '.' . $user->avatar_extension;
                $filePath = storage_path('app/tmp/' . $filename);
                $targetDirectory = storage_path('app/public/users/avatars');
                File::ensureDirectoryExists($targetDirectory, 0755, true);

                if (File::exists($filePath)) {
                    File::move($filePath, $targetDirectory . '/' . $filename);
                }

                $current_avatar_path =
                    $targetDirectory .
                    '/' .
                    $user->getOriginal('avatar') .
                    '.' .
                    $user->getOriginal('avatar_extension');
                if (File::exists($current_avatar_path)) {
                    File::delete($current_avatar_path);
                }
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'tag',
        'avatar',
        'avatar_extension',
        'social_linkedin',
        'social_github',
        'social_facebook',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'isOwner',
        'email_verified_at',
        'updated_at',
        'id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $with = ['skills', 'files'];

    protected $appends = ['profileCompletion'];

    public function companies()
    {
        return $this->hasMany(Company::class, 'owner');
    }

    public function getIsOwnerAttribute()
    {
        return $this->companies()->count() > 0;
    }

    public function jobHistory()
    {
        return $this->hasMany(JobHistory::class);
    }

    public function educationHistory()
    {
        return $this->hasMany(EducationHistory::class);
    }

    public function userDescription()
    {
        return $this->hasMany(UserDescription::class);
    }

    public function getLocalizedDataAttribute($lang)
    {
        return [
            'language' => $lang,
            'jobHistory' => $this->jobHistory()
                ->where('locale', $lang)
                ->get(),
            'educationHistory' => $this->educationHistory()
                ->where('locale', $lang)
                ->get(),
            'description' => $this->userDescription()
                ->where('locale', $lang)
                ->value('description'),
        ];
    }

    public function skills()
    {
        return $this->belongsToMany(
            Skill::class,
            'user_skill'
        )->withTimestamps();
    }

    public function files()
    {
        return $this->hasMany(UserFile::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function alreadyApplied(Job $job)
    {
        return $this->applications()
            ->where('job_id', $job->id)
            ->exists();
    }

    public function getProfileCompletionAttribute()
    {
        $totalFields = 7;
        $completedFields = 0;

        if ($this->avatar) {
            $completedFields++;
        }
        if (
            $this->social_linkedin ||
            $this->social_github ||
            $this->social_facebook
        ) {
            $completedFields++;
        }
        if ($this->userDescription()->exists()) {
            $completedFields++;
        }
        if ($this->jobHistory()->exists()) {
            $completedFields++;
        }
        if ($this->educationHistory()->exists()) {
            $completedFields++;
        }
        if ($this->skills()->exists()) {
            $completedFields++;
        }
        if ($this->files()->exists()) {
            $completedFields++;
        }

        return ($completedFields / $totalFields) * 100;
    }
}
