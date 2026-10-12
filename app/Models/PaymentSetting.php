<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'method',
        'display_name',
        'account_title',
        'account_number',
        'bank_name',
        'iban',
        'branch_code',
        'instructions',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get only active payment methods.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
