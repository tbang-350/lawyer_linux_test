<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'website',
        'description',
        'default_notification_settings',
    ];

    protected $casts = [
        'default_notification_settings' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function lawyers()
    {
        return $this->hasMany(User::class)->where('role', 'lawyer');
    }

    public function admins()
    {
        return $this->hasMany(User::class)->where('role', 'admin');
    }
}
