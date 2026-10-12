<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\PaymentSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Show payment submission form for an appointment.
     */
    public function create($appointmentId)
    {
        $appointment = Appointment::with([
            'lawyer.lawyerProfile',
            'service',
            'payment',
        ])
            ->whereKey($appointmentId)
            ->where('customer_id', Auth::id())
            ->firstOrFail();

        if ($appointment->status === 'cancelled') {
            return redirect()
                ->route('customer.dashboard')
                ->with('error', 'Cannot make payment for a cancelled appointment.');
        }

        $existingPayment = $appointment->payment;

        if ($existingPayment?->isVerified()) {
            return redirect()
                ->route('customer.payment.show', $existingPayment->id)
                ->with('info', 'Payment for this appointment has already been verified.');
        }

        if ($existingPayment?->isPending()) {
            return redirect()
                ->route('customer.payment.show', $existingPayment->id)
                ->with('info', 'A payment submission is already pending verification for this appointment.');
        }

        $lawyerProfile = $appointment->lawyer?->lawyerProfile;

        $payableAmount = $lawyerProfile
            ? (float) $lawyerProfile->hourly_fee
            : 150.00;

        $currency = '$';

        $paymentSettings = PaymentSetting::active()
            ->get()
            ->keyBy('method');

        // Do not display a payment form when the administrator has disabled
        // every payment method.
        if ($paymentSettings->isEmpty()) {
            return redirect()
                ->route('customer.dashboard')
                ->with('error', 'No payment method is currently available. Please contact the administrator.');
        }

        return view('customer.payment', compact(
            'appointment',
            'payableAmount',
            'currency',
            'paymentSettings',
            'existingPayment'
        ));
    }

    /**
     * Store submitted payment details.
     */
    public function store(Request $request, $appointmentId)
    {
        $activeMethods = PaymentSetting::active()
            ->pluck('method')
            ->filter()
            ->values()
            ->all();

        if (empty($activeMethods)) {
            return redirect()
                ->route('customer.dashboard')
                ->with('error', 'No payment method is currently available. Please contact the administrator.');
        }

        $validated = $request->validate([
            'payment_method' => [
                'required',
                'string',
                'in:' . implode(',', $activeMethods),
            ],
            'transaction_id' => [
                'required',
                'string',
                'max:100',
            ],
            'sender_name' => [
                'required',
                'string',
                'max:150',
            ],
            'sender_account' => [
                'required',
                'string',
                'max:100',
            ],
            'payment_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'payment_proof' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:5120',
            ],
        ]);

        $proofPath = null;

        try {
            $payment = DB::transaction(function () use (
                $validated,
                $request,
                $appointmentId,
                &$proofPath
            ) {
                // Lock the appointment while checking/creating the payment so
                // two simultaneous submissions cannot create two active payments.
                $appointment = Appointment::with([
                    'lawyer.lawyerProfile',
                    'payment',
                ])
                    ->whereKey($appointmentId)
                    ->where('customer_id', Auth::id())
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($appointment->status === 'cancelled') {
                    abort(422, 'Cannot submit payment for a cancelled appointment.');
                }

                $existingPayment = $appointment->payment;

                if ($existingPayment?->isPending() || $existingPayment?->isVerified()) {
                    abort(422, 'This appointment already has an active or verified payment.');
                }

                $lawyerProfile = $appointment->lawyer?->lawyerProfile;

                $payableAmount = $lawyerProfile
                    ? (float) $lawyerProfile->hourly_fee
                    : 150.00;

                $proofPath = $request->file('payment_proof')
                    ->store('payment-proofs', 'public');

                return Payment::create([
                    'appointment_id' => $appointment->id,
                    'customer_id' => Auth::id(),
                    'payment_method' => trim($validated['payment_method']),
                    'transaction_id' => trim($validated['transaction_id']),
                    'sender_name' => trim($validated['sender_name']),
                    'sender_account' => trim($validated['sender_account']),
                    'payment_date' => $validated['payment_date'],
                    'amount' => $payableAmount,
                    'currency' => '$',
                    'payment_proof' => $proofPath,
                    'status' => 'pending',
                ]);
            });
        } catch (\Throwable $e) {
            // Remove an uploaded proof if payment creation failed.
            if ($proofPath && Storage::disk('public')->exists($proofPath)) {
                Storage::disk('public')->delete($proofPath);
            }

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                return back()
                    ->withInput()
                    ->with('error', $e->getMessage());
            }

            throw $e;
        }

        return redirect()
            ->route('customer.payment.show', $payment->id)
            ->with('success', 'Payment proof submitted successfully! It is now pending administrative verification.');
    }

    /**
     * Show customer payment details.
     */
    public function show($id)
    {
        $payment = Payment::with([
            'appointment.lawyer.lawyerProfile',
            'appointment.service',
            'customer',
            'verifier',
        ])
            ->whereKey($id)
            ->where('customer_id', Auth::id())
            ->firstOrFail();

        return view('customer.payment-show', compact('payment'));
    }

    /**
     * Securely serve payment proof to its owner or an administrator.
     *
     * This route is intentionally available to authenticated users rather
     * than only the customer role because admin payment screens also use it.
     */
    public function proof($id)
    {
        $payment = Payment::findOrFail($id);

        $user = Auth::user();

        $isOwner = $user && (int) $user->id === (int) $payment->customer_id;
        $isAdmin = $user && $user->isAdmin();

        if (!$isOwner && !$isAdmin) {
            abort(403, 'Unauthorized access to payment proof.');
        }

        if (
            !$payment->payment_proof ||
            !Storage::disk('public')->exists($payment->payment_proof)
        ) {
            abort(404, 'Payment proof file not found.');
        }

        return Storage::disk('public')->response($payment->payment_proof);
    }

    /**
     * Show verified receipt.
     */
    public function receipt($id)
    {
        $payment = Payment::with([
            'appointment.lawyer.lawyerProfile',
            'appointment.service',
            'customer',
            'verifier',
        ])->findOrFail($id);

        $user = Auth::user();

        $isOwner = $user && (int) $user->id === (int) $payment->customer_id;
        $isAdmin = $user && $user->isAdmin();

        if (!$isOwner && !$isAdmin) {
            abort(403, 'Unauthorized access to receipt.');
        }

        if (!$payment->isVerified()) {
            if ($isAdmin) {
                return redirect()
                    ->route('admin.payments.show', $payment->id)
                    ->with('error', 'Receipt is only available for verified payments.');
            }

            return redirect()
                ->route('customer.payment.show', $payment->id)
                ->with('error', 'Receipt is only available for verified payments.');
        }

        return view('receipts.view', compact('payment'));
    }

    /**
     * Download verified receipt as PDF.
     */
    public function downloadReceipt($id)
    {
        $payment = Payment::with([
            'appointment.lawyer.lawyerProfile',
            'appointment.service',
            'customer',
            'verifier',
        ])->findOrFail($id);

        $user = Auth::user();

        $isOwner = $user && (int) $user->id === (int) $payment->customer_id;
        $isAdmin = $user && $user->isAdmin();

        if (!$isOwner && !$isAdmin) {
            abort(403, 'Unauthorized access to receipt.');
        }

        if (!$payment->isVerified()) {
            if ($isAdmin) {
                return redirect()
                    ->route('admin.payments.show', $payment->id)
                    ->with('error', 'Receipt is only available for verified payments.');
            }

            return redirect()
                ->route('customer.payment.show', $payment->id)
                ->with('error', 'Receipt is only available for verified payments.');
        }

        $pdf = Pdf::loadView('receipts.pdf', compact('payment'))
            ->setPaper('a4', 'portrait');

        $receiptNumber = $payment->receipt_number ?: 'PAY-' . $payment->id;

        $filename = 'Receipt-' . Str::slug($receiptNumber) . '.pdf';

        return $pdf->download($filename);
    }
}
