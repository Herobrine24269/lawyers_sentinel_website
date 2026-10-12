<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_number',
        'customer_id',
        'lawyer_id',
        'service_id',
        'appointment_date',
        'start_time',
        'end_time',
        'meeting_place',
        'notes',
        'status', // 'pending', 'confirmed', 'completed', 'cancelled'
        'cancellation_reason',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'appointment_id')->latestOfMany();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'appointment_id');
    }
}
