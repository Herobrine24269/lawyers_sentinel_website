<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LawyerSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'lawyer_id',
        'day_of_week', // Monday, Tuesday, etc.
        'start_time',
        'end_time',
        'slot_duration_minutes',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }
}
