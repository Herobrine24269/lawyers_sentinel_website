<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_pic',
        'role', // 'admin', 'lawyer', 'customer'
        'status', // 'active', 'pending', 'suspended'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'profile_pic_url',
    ];

    public function getProfilePicUrlAttribute(): string
    {
        if ($this->profile_pic && file_exists(storage_path('app/public/' . $this->profile_pic))) {
            return asset('storage/' . $this->profile_pic);
        }

        $name = urlencode($this->name ?? 'User');
        $bg = $this->isLawyer() ? '0F172A' : ($this->isAdmin() ? '581C87' : '1E40AF');
        $color = 'FFFFFF';
        return "https://ui-avatars.com/api/?name={$name}&background={$bg}&color={$color}&size=200&bold=true";
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isLawyer(): bool
    {
        return $this->role === 'lawyer';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function lawyerProfile()
    {
        return $this->hasOne(LawyerProfile::class, 'user_id');
    }

    public function lawyerSchedules()
    {
        return $this->hasMany(LawyerSchedule::class, 'lawyer_id');
    }

    public function customerAppointments()
    {
        return $this->hasMany(Appointment::class, 'customer_id');
    }

    public function lawyerAppointments()
    {
        return $this->hasMany(Appointment::class, 'lawyer_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'customer_id');
    }

    public function verifiedPayments()
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
}
