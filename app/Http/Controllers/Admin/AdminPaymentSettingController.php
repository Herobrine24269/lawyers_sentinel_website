<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentSetting;

class AdminPaymentSettingController extends Controller
{
    /**
     * Display payment configuration interface.
     */
    public function index()
    {
        $settings = PaymentSetting::all()->keyBy('method');
        return view('admin.payments.settings', compact('settings'));
    }

    /**
     * Update payment configuration for Easypaisa, JazzCash, Bank Transfer.
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => ['required', 'array'],
            'settings.easypaisa.account_title' => ['nullable', 'string', 'max:150'],
            'settings.easypaisa.account_number' => ['nullable', 'string', 'max:100'],
            'settings.easypaisa.instructions' => ['nullable', 'string', 'max:2000'],

            'settings.jazzcash.account_title' => ['nullable', 'string', 'max:150'],
            'settings.jazzcash.account_number' => ['nullable', 'string', 'max:100'],
            'settings.jazzcash.instructions' => ['nullable', 'string', 'max:2000'],

            'settings.bank_transfer.bank_name' => ['nullable', 'string', 'max:150'],
            'settings.bank_transfer.account_title' => ['nullable', 'string', 'max:150'],
            'settings.bank_transfer.account_number' => ['nullable', 'string', 'max:100'],
            'settings.bank_transfer.iban' => ['nullable', 'string', 'max:100'],
            'settings.bank_transfer.branch_code' => ['nullable', 'string', 'max:100'],
            'settings.bank_transfer.instructions' => ['nullable', 'string', 'max:2000'],
        ]);

        $methods = ['easypaisa', 'jazzcash', 'bank_transfer'];

        foreach ($methods as $method) {
            $data = $request->input("settings.{$method}", []);
            $isActive = $request->has("settings.{$method}.is_active");

            PaymentSetting::updateOrCreate(
                ['method' => $method],
                [
                    'display_name' => match($method) {
                        'easypaisa' => 'Easypaisa Mobile Account',
                        'jazzcash' => 'JazzCash Mobile Account',
                        'bank_transfer' => 'Direct Bank Transfer / Online Wire',
                        default => ucfirst($method)
                    },
                    'account_title' => $data['account_title'] ?? null,
                    'account_number' => $data['account_number'] ?? null,
                    'bank_name' => $data['bank_name'] ?? null,
                    'iban' => $data['iban'] ?? null,
                    'branch_code' => $data['branch_code'] ?? null,
                    'instructions' => $data['instructions'] ?? null,
                    'is_active' => $isActive,
                ]
            );
        }

        return back()->with('success', 'Payment gateway settings and account instructions updated successfully.');
    }
}
