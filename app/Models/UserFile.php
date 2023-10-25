<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class UserFile extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::created(function ($userFile) {
            $sourceDirectory = storage_path('app/tmp');
            $targetDirectory = storage_path('app/private/users/files');
            if (!File::exists($targetDirectory)) {
                File::makeDirectory($targetDirectory, 0755, true);
            }
            $currentFilePath =
                $sourceDirectory .
                '/' .
                $userFile->servername .
                '.' .
                $userFile->extension;
            if (File::exists($currentFilePath)) {
                File::move(
                    $currentFilePath,
                    $targetDirectory . '/' . $userFile->servername
                );
            }
        });
        static::deleted(function ($userFile) {
            $targetDirectory = storage_path('app/private/users/files');
            $currentFilePath =
                $targetDirectory .
                '/' .
                $userFile->servername .
                '.' .
                $userFile->extension;
            if (File::exists($currentFilePath)) {
                File::delete($currentFilePath);
            }
        });
    }
    protected $fillable = ['user_id', 'name', 'extension', 'servername'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
