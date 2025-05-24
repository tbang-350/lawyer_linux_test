<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_notifications',
        'reminder_timings',
        'daily_digest_time',
        'receive_daily_digest',
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'reminder_timings' => 'array',
        'daily_digest_time' => 'datetime',
        'receive_daily_digest' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
