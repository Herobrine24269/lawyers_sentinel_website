@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('customer.dashboard') }}" class="text-xs font-bold text-slate-600 hover:text-blue-600 transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>
        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-800 font-bold text-xs px-3.5 py-2 rounded-lg transition flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-print"></i> Print Receipt
            </button>
            <a href="{{ route('customer.payment.receipt.download', $payment->id) }}" class="bg-navy-900 hover:bg-blue-600 text-white font-bold text-xs px-4 py-2 rounded-lg transition flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-download"></i> Download PDF
            </a>
        </div>
    </div>

    <!-- Official Printable Receipt Paper Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-lg p-8 sm:p-12 relative overflow-hidden" id="receiptPaper">
        
        <!-- Top Juris Branding Bar -->
        <div class="border-b-2 border-navy-900 pb-6 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-navy-900 text-amber-400 flex items-center justify-center text-2xl shadow-sm">
                    <i class="fa-solid fa-gavel"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-navy-900">JURIS SENTINEL</h1>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Lawyer Appointment Portal</p>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <span class="bg-emerald-100 text-emerald-900 font-bold text-xs px-3 py-1 rounded-full border border-emerald-300 uppercase tracking-wider inline-flex items-center gap-1">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i> Payment Verified
                </span>
                <div class="text-[11px] font-mono text-slate-500 mt-1">Receipt #: <strong class="text-navy-900">{{ $payment->receipt_number }}</strong></div>
            </div>
        </div>

        <div class="text-center my-6">
            <h2 class="text-lg font-extrabold uppercase tracking-wide text-navy-900">Official Consultation Payment Receipt</h2>
            <p class="text-xs text-slate-500 mt-0.5">System-reconciled proof of consultation booking payment</p>
        </div>

        <!-- Receipt Meta Information Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs mb-8">
            <div>
                <span class="text-slate-400 text-[10px] uppercase font-bold block">Receipt Number</span>
                <span class="font-mono font-bold text-blue-700 text-xs sm:text-sm">{{ $payment->receipt_number }}</span>
            </div>
            <div>
                <span class="text-slate-400 text-[10px] uppercase font-bold block">Payment Date</span>
                <span class="font-bold text-slate-800">{{ date('M j, Y', strtotime($payment->payment_date)) }}</span>
            </div>
            <div>
                <span class="text-slate-400 text-[10px] uppercase font-bold block">Payment Method</span>
                <span class="font-bold text-slate-800">{{ $payment->payment_method_name }}</span>
            </div>
            <div>
                <span class="text-slate-400 text-[10px] uppercase font-bold block">Transaction ID (TID)</span>
                <span class="font-mono font-bold text-navy-900">{{ $payment->transaction_id }}</span>
            </div>
        </div>

        <!-- Two Column Customer & Attorney Information -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs mb-8">
            
            <!-- Customer Details -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-2">
                <span class="text-[10px] font-extrabold text-blue-600 uppercase tracking-wider block border-b border-slate-150 pb-1.5">
                    Customer Information
                </span>
                <div>
                    <span class="font-bold text-navy-900 text-sm block">{{ $payment->customer->name }}</span>
                    <span class="text-slate-600 block">{{ $payment->customer->email }}</span>
                    <span class="text-slate-500 block text-[11px]">{{ $payment->customer->phone ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Lawyer Details -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-2">
                <span class="text-[10px] font-extrabold text-amber-600 uppercase tracking-wider block border-b border-slate-150 pb-1.5">
                    Attorney Information
                </span>
                <div>
                    <span class="font-bold text-navy-900 text-sm block">{{ $payment->appointment->lawyer->name ?? 'Lawyer' }}</span>
                    <span class="text-slate-600 block">{{ $payment->appointment->lawyer->email ?? '' }}</span>
                    <span class="text-slate-500 block text-[11px]"><i class="fa-solid fa-location-dot mr-1"></i> {{ $payment->appointment->lawyer->lawyerProfile->location ?? 'City' }} &bull; License: {{ $payment->appointment->lawyer->lawyerProfile->license_number ?? 'N/A' }}</span>
                </div>
            </div>

        </div>

        <!-- Consultation Booking Table Breakdown -->
        <div class="border border-slate-200 rounded-xl overflow-hidden mb-8 text-xs">
            <table class="w-full text-left">
                <thead class="bg-navy-900 text-white font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="p-3.5">Consultation Item</th>
                        <th class="p-3.5">Scheduled Slot</th>
                        <th class="p-3.5">Format</th>
                        <th class="p-3.5 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    <tr>
                        <td class="p-3.5 font-medium">
                            <span class="font-bold text-navy-900 block">{{ $payment->appointment->service->name ?? 'General Legal Counsel' }}</span>
                            <span class="text-[11px] text-slate-500">Appt Code: <strong class="font-mono text-blue-700">{{ $payment->appointment->appointment_number }}</strong></span>
                        </td>
                        <td class="p-3.5">
                            <div class="font-bold">{{ date('D, M j, Y', strtotime($payment->appointment->appointment_date)) }}</div>
                            <div class="text-[11px] text-slate-500">{{ date('g:i A', strtotime($payment->appointment->start_time)) }} - {{ date('g:i A', strtotime($payment->appointment->end_time)) }}</div>
                        </td>
                        <td class="p-3.5 font-semibold text-slate-700">{{ $payment->appointment->meeting_place }}</td>
                        <td class="p-3.5 text-right font-extrabold text-slate-900 font-mono text-sm">
                            {{ $payment->currency }}{{ number_format($payment->amount, 2) }}
                        </td>
                    </tr>
                </tbody>
                <tfoot class="bg-slate-50 border-t border-slate-200 font-bold">
                    <tr>
                        <td colspan="3" class="p-3.5 text-right uppercase tracking-wider text-slate-600">Total Paid Amount:</td>
                        <td class="p-3.5 text-right font-extrabold text-navy-900 text-base font-mono">
                            {{ $payment->currency }}{{ number_format($payment->amount, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Verification Stamp & Sign-off -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 border border-slate-200 p-5 rounded-xl text-xs mb-8">
            <div class="space-y-1">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Verification Audit</span>
                <div class="text-slate-800 font-bold flex items-center gap-1.5">
                    <i class="fa-solid fa-stamp text-emerald-600"></i> Verified by: {{ $payment->verifier->name ?? 'Juris System Admin' }}
                </div>
                <div class="text-[11px] text-slate-500">
                    Timestamp: {{ $payment->verified_at ? $payment->verified_at->format('M j, Y \a\t g:i:s A') : date('M j, Y') }}
                </div>
            </div>
            <div class="space-y-1 sm:text-right">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Sender Confirmation</span>
                <div class="text-slate-800 font-semibold">Account: <span class="font-mono font-bold">{{ $payment->sender_account }}</span> ({{ $payment->sender_name }})</div>
                <div class="text-[11px] text-slate-500">Status: <strong class="text-emerald-700 uppercase">Paid & Verified</strong></div>
            </div>
        </div>

        <!-- Official Footer Note -->
        <div class="text-center border-t border-slate-200 pt-6 text-[11px] text-slate-400">
            <p class="font-medium text-slate-500">This is an electronically generated and system-verified payment receipt.</p>
            <p class="mt-0.5">Juris Sentinel &bull; Secure Legal Consultation Booking & Verification System &bull; All Rights Reserved</p>
        </div>

    </div>

</div>
@endsection
