@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-4">
        <a href="{{ route('customer.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-slate-800">Payment Details</span>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-4 rounded-xl mb-6 flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('info'))
    <div class="bg-blue-50 border border-blue-200 text-blue-800 text-xs p-4 rounded-xl mb-6 flex items-center gap-2">
        <i class="fa-solid fa-circle-info text-blue-600 text-sm"></i>
        <span>{{ session('info') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 text-xs p-4 rounded-xl mb-6 flex items-center gap-2">
        <i class="fa-solid fa-triangle-exclamation text-red-600 text-sm"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Payment Status Hero Header -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-150">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-2xl font-extrabold text-navy-900">Payment #{{ $payment->receipt_number ?: 'PAY-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</h1>
                    @if($payment->isVerified())
                        <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full border border-emerald-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Verified
                        </span>
                    @elseif($payment->isPending())
                        <span class="bg-amber-100 text-amber-900 text-xs font-bold px-3 py-1 rounded-full border border-amber-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-clock-rotate-left text-amber-600"></i> Pending Verification
                        </span>
                    @else
                        <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full border border-red-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-xmark text-red-600"></i> Rejected
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-1">Submitted on {{ $payment->created_at->format('M j, Y \a\t g:i A') }}</p>
            </div>

            <!-- Header Action Buttons -->
            <div class="flex items-center gap-2 self-start sm:self-auto">
                @if($payment->isVerified())
                    <a href="{{ route('customer.payment.receipt', $payment->id) }}" class="bg-navy-900 hover:bg-blue-600 text-white font-bold text-xs px-4 py-2.5 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa-solid fa-file-invoice"></i> View Receipt
                    </a>
                    <a href="{{ route('customer.payment.receipt.download', $payment->id) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa-solid fa-download"></i> Download PDF
                    </a>
                @elseif($payment->isRejected())
                    <a href="{{ route('customer.payment.create', $payment->appointment_id) }}" class="bg-blue-600 hover:bg-navy-900 text-white font-bold text-xs px-4 py-2.5 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa-solid fa-arrows-rotate"></i> Submit Payment Again
                    </a>
                @endif
            </div>
        </div>

        <!-- Status Notice Banner -->
        @if($payment->isVerified())
        <div class="mt-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-xs text-emerald-950 flex items-start gap-3">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base mt-0.5"></i>
            <div>
                <span class="font-bold block text-emerald-900">Payment Successfully Verified & Approved</span>
                <span class="text-emerald-800">Your payment has been reconciled. Your appointment slot is confirmed. Receipt Number: <strong class="font-mono">{{ $payment->receipt_number }}</strong>.</span>
                <div class="text-[11px] text-emerald-700 mt-1">Verified at {{ $payment->verified_at ? $payment->verified_at->format('M j, Y g:i A') : '' }} by {{ $payment->verifier->name ?? 'System Administrator' }}</div>
            </div>
        </div>
        @elseif($payment->isPending())
        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-xl p-4 text-xs text-amber-950 flex items-start gap-3">
            <i class="fa-solid fa-hourglass-half text-amber-600 text-base mt-0.5"></i>
            <div>
                <span class="font-bold block text-amber-900">Verification in Progress</span>
                <span class="text-amber-800">Our administrative team is reviewing your transaction credentials against bank/wallet records. This typically takes a few minutes.</span>
            </div>
        </div>
        @else
        <div class="mt-6 bg-red-50 border border-red-200 rounded-xl p-4 text-xs text-red-950 flex items-start gap-3">
            <i class="fa-solid fa-triangle-exclamation text-red-600 text-base mt-0.5"></i>
            <div class="space-y-1">
                <span class="font-bold block text-red-900">Payment Verification Rejected</span>
                <span class="text-red-800"><strong>Admin Remarks:</strong> {{ $payment->admin_notes ?: 'Unable to verify transaction details in official ledger.' }}</span>
                <p class="text-[11px] text-red-700 mt-1">You can submit a corrected payment proof or transaction ID using the button below.</p>
                <div class="pt-2">
                    <a href="{{ route('customer.payment.create', $payment->appointment_id) }}" class="inline-flex items-center gap-1.5 bg-red-700 hover:bg-red-800 text-white font-bold text-xs px-4 py-2 rounded-lg transition">
                        <i class="fa-solid fa-rotate-right"></i> Re-submit Payment Details
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 text-xs">
            
            <!-- Appointment Card -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 space-y-3">
                <div class="font-extrabold text-navy-900 pb-2 border-b border-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-blue-600"></i> Appointment Information
                </div>

                <div class="flex justify-between py-1">
                    <span class="text-slate-500">Appointment Code:</span>
                    <span class="font-mono font-bold text-blue-700">{{ $payment->appointment->appointment_number }}</span>
                </div>

                <div class="flex justify-between py-1">
                    <span class="text-slate-500">Assigned Attorney:</span>
                    <span class="font-bold text-slate-800">{{ $payment->appointment->lawyer->name ?? 'Lawyer' }}</span>
                </div>

                <div class="flex justify-between py-1">
                    <span class="text-slate-500">Practice Area:</span>
                    <span class="font-semibold text-slate-700">{{ $payment->appointment->service->name ?? 'General Counsel' }}</span>
                </div>

                <div class="flex justify-between py-1">
                    <span class="text-slate-500">Appointment Date:</span>
                    <span class="font-bold text-slate-800">{{ date('M j, Y', strtotime($payment->appointment->appointment_date)) }}</span>
                </div>

                <div class="flex justify-between py-1">
                    <span class="text-slate-500">Time Slot:</span>
                    <span class="font-semibold text-slate-700">{{ date('g:i A', strtotime($payment->appointment->start_time)) }} - {{ date('g:i A', strtotime($payment->appointment->end_time)) }}</span>
                </div>

                <div class="flex justify-between py-1">
                    <span class="text-slate-500">Meeting Format:</span>
                    <span class="font-semibold text-slate-700">{{ $payment->appointment->meeting_place }}</span>
                </div>
            </div>

            <!-- Transaction Credentials -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 space-y-3">
                <div class="font-extrabold text-navy-900 pb-2 border-b border-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-emerald-600"></i> Payment Credentials
                </div>

                <div class="flex justify-between py-1">
                    <span class="text-slate-500">Payable Amount:</span>
                    <span class="font-extrabold text-navy-900 text-sm">{{ $payment->currency }}{{ number_format($payment->amount, 2) }}</span>
                </div>

                <div class="flex justify-between py-1">
                    <span class="text-slate-500">Payment Method:</span>
                    <span class="font-bold text-slate-800 capitalize">{{ $payment->payment_method_name }}</span>
                </div>

                <div class="flex justify-between py-1">
                    <span class="text-slate-500">Transaction ID (TID):</span>
                    <span class="font-mono font-bold text-blue-700 uppercase">{{ $payment->transaction_id }}</span>
                </div>

                <div class="flex justify-between py-1">
                    <span class="text-slate-500">Sender Name:</span>
                    <span class="font-semibold text-slate-800">{{ $payment->sender_name }}</span>
                </div>

                <div class="flex justify-between py-1">
                    <span class="text-slate-500">Sender Mobile / Acct:</span>
                    <span class="font-mono font-semibold text-slate-800">{{ $payment->sender_account }}</span>
                </div>

                <div class="flex justify-between py-1">
                    <span class="text-slate-500">Payment Date:</span>
                    <span class="font-semibold text-slate-800">{{ date('M j, Y', strtotime($payment->payment_date)) }}</span>
                </div>
            </div>

        </div>

        <!-- Payment Proof Screenshot Box -->
        <div class="mt-8 border-t border-slate-200 pt-6">
            <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-600 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-image text-slate-500"></i> Submitted Proof Attachment
            </h3>

            @if($payment->payment_proof)
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('customer.payment.proof', $payment->id) }}" target="_blank" class="block group">
                            <img src="{{ route('customer.payment.proof', $payment->id) }}" alt="Payment Proof Screenshot" class="w-20 h-20 rounded-lg object-cover border border-slate-300 shadow-sm group-hover:opacity-90 transition">
                        </a>
                        <div>
                            <span class="font-bold text-xs text-navy-900 block">Payment Transfer Receipt Screenshot</span>
                            <span class="text-[11px] text-slate-500">Uploaded proof document</span>
                        </div>
                    </div>
                    <a href="{{ route('customer.payment.proof', $payment->id) }}" target="_blank" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-800 font-bold text-xs px-4 py-2 rounded-lg transition inline-flex items-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Open Full Image
                    </a>
                </div>
            @else
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs text-slate-500 text-center">
                    No image proof attached.
                </div>
            @endif
        </div>

    </div>

    <!-- Back to Dashboard Footer Link -->
    <div class="flex items-center justify-between text-xs">
        <a href="{{ route('customer.dashboard') }}" class="text-blue-600 hover:underline font-bold flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to My Appointments
        </a>
    </div>

</div>
@endsection
