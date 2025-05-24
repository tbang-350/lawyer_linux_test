<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'court_location',
        'case_number',
        'case_type',
        'status',
        'reminder_settings',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'reminder_settings' => 'array',
    ];

    public function lawyers()
    {
        return $this->belongsToMany(User::class, 'appointment_lawyer')
            ->withPivot('notification_preferences')
            ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now())
            ->where('status', 'scheduled');
    }

    public function scopePast($query)
    {
        return $query->where('start_time', '<', now())
            ->orWhere('status', 'completed');
    }
}
