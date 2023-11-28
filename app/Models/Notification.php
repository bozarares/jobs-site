<?php

namespace App\Models;

use App\Notifications\RealTimeNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        static::created(function ($notification) {
            $notification->user->notify(
                new RealTimeNotification($notification->message)
            );
        });
    }

    protected $fillable = ['user_id', 'message', 'read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
