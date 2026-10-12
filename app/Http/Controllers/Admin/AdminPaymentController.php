<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminPaymentController extends Controller
{
    /**
     * Display all submitted payments with filters.
     */
    public function index(Request $request)
    {
        $query = Payment::with([
            'appointment.lawyer.lawyerProfile',
            'appointment.service',
            'customer',
            'verifier',
        ]);

        if (
            $request->filled('status') &&
            in_array($request->input('status'), ['pending', 'verified', 'rejected'], true)
        ) {
            $query->where('status', $request->input('status'));
        }

        if (
            $request->filled('method') &&
            in_array($request->input('method'), ['easypaisa', 'jazzcash', 'bank_transfer'], true)
        ) {
            $query->where('payment_method', $request->input('method'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->input('date_to'));
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('receipt_number', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%")
                    ->orWhere('sender_name', 'like', "%{$search}%")
                    ->orWhere('sender_account', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('appointment', function ($aq) use ($search) {
                        $aq->where('appointment_number', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalPaymentsCount = Payment::count();
        $pendingCount = Payment::where('status', 'pending')->count();
        $verifiedCount = Payment::where('status', 'verified')->count();
        $rejectedCount = Payment::where('status', 'rejected')->count();
        $totalRevenue = Payment::where('status', 'verified')->sum('amount');

        return view('admin.payments.index', compact(
            'payments',
            'totalPaymentsCount',
            'pendingCount',
            'verifiedCount',
            'rejectedCount',
            'totalRevenue'
        ));
    }

    /**
     * Show detailed payment view.
     */
    public function show($id)
    {
        $payment = Payment::with([
            'appointment.lawyer.lawyerProfile',
            'appointment.service',
            'customer',
            'verifier',
        ])->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Verify a pending payment and generate a unique receipt.
     */
    public function verify(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:500'],
            'auto_confirm_appointment' => ['nullable', 'boolean'],
        ]);

        $payment = DB::transaction(function () use ($id, $validated, $request) {
            $payment = Payment::with('appointment')
                ->whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            // Never re-verify an already verified/rejected payment.
            if (!$payment->isPending()) {
                abort(422, 'Only pending payments can be verified.');
            }

            $receiptNumber = $this->generateReceiptNumber();

            $payment->update([
                'status' => 'verified',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'receipt_number' => $receiptNumber,
                'admin_notes' => filled($validated['admin_notes'] ?? null)
                    ? trim($validated['admin_notes'])
                    : 'Payment verified and approved by Administrator.',
            ]);

            // Confirm the appointment after successful verification.
            if (
                $request->boolean('auto_confirm_appointment') ||
                $payment->appointment?->status === 'pending'
            ) {
                $payment->appointment?->update([
                    'status' => 'confirmed',
                ]);
            }

            return $payment->fresh();
        });

        return back()->with(
            'success',
            "Payment {$payment->transaction_id} verified successfully! Receipt #{$payment->receipt_number} generated."
        );
    }

    /**
     * Reject a pending payment.
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_notes' => ['required', 'string', 'max:500'],
        ]);

        $payment = Payment::whereKey($id)->firstOrFail();

        if (!$payment->isPending()) {
            return back()->with(
                'error',
                'Only pending payments can be rejected.'
            );
        }

        $payment->update([
            'status' => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'admin_notes' => trim($validated['admin_notes']),
        ]);

        return back()->with(
            'success',
            'Payment has been marked as rejected. The reason has been recorded for customer review.'
        );
    }

    /**
     * Generate a short unique receipt number.
     */
    private function generateReceiptNumber(): string
    {
        do {
            $receiptNumber = 'PAY-' . now()->format('Y') . '-' . Str::upper(Str::random(5));
        } while (Payment::where('receipt_number', $receiptNumber)->exists());

        return $receiptNumber;
    }
}
