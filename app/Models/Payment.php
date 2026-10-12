<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'customer_id',
        'payment_method', // 'easypaisa', 'jazzcash', 'bank_transfer'
        'transaction_id',
        'sender_name',
        'sender_account',
        'payment_date',
        'amount',
        'currency',
        'payment_proof',
        'status', // 'pending', 'verified', 'rejected'
        'admin_notes',
        'verified_by',
        'verified_at',
        'receipt_number',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'verified_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getPaymentMethodNameAttribute(): string
    {
        return match($this->payment_method) {
            'easypaisa' => 'Easypaisa',
            'jazzcash' => 'JazzCash',
            'bank_transfer' => 'Bank Transfer',
            default => ucfirst(str_replace('_', ' ', $this->payment_method)),
        };
    }
}
