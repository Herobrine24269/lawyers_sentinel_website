<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LawyerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location',
        'meeting_place', // e.g. "Office, Virtual, Client Premises"
        'specializations', // JSON array of service IDs or names
        'experience_years',
        'license_number',
        'hourly_fee',
        'bio',
        'rating',
        'total_reviews',
        'is_featured',
        'is_approved',
    ];

    protected $casts = [
        'specializations' => 'array',
        'is_featured' => 'boolean',
        'is_approved' => 'boolean',
        'hourly_fee' => 'decimal:2',
        'rating' => 'decimal:1',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
