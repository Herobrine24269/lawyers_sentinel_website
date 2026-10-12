@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="{{ route('admin.payments') }}" class="hover:text-blue-600 transition">Payment Management</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-slate-800">Payment Details #{{ $payment->receipt_number ?: 'PAY-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</span>
    </div>

    <!-- Header Card -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl font-extrabold text-slate-900">
                    Payment #{{ $payment->receipt_number ?: 'PAY-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}
                </h1>
                @if($payment->isVerified())
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full border border-emerald-300 flex items-center gap-1">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i> Verified & Reconciled
                    </span>
                @elseif($payment->isPending())
                    <span class="bg-amber-100 text-amber-900 text-xs font-bold px-3 py-1 rounded-full border border-amber-300 flex items-center gap-1">
                        <i class="fa-solid fa-clock-rotate-left text-amber-600"></i> Pending Verification
                    </span>
                @else
                    <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full border border-red-300 flex items-center gap-1">
                        <i class="fa-solid fa-circle-xmark text-red-600"></i> Rejected
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-1">Submitted: {{ $payment->created_at->format('M j, Y \a\t g:i:s A') }}</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @if($payment->isVerified())
                <a href="{{ route('customer.payment.receipt', $payment->id) }}" target="_blank" class="bg-navy-900 hover:bg-blue-600 text-white font-bold text-xs px-4 py-2 rounded-lg transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-receipt"></i> Official Receipt
                </a>
                <a href="{{ route('customer.payment.receipt.download', $payment->id) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2 rounded-lg transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-download"></i> Download PDF
                </a>
            @endif
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Transaction & Customer Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Payment Data -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4 text-xs">
                <h3 class="font-extrabold text-sm text-slate-900 pb-3 border-b border-slate-150 flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice-dollar text-emerald-600"></i> Financial & Transaction Information
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-slate-50 p-3.5 rounded-lg border border-slate-200">
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Payable Consultation Fee</span>
                        <span class="text-lg font-extrabold text-navy-900 font-mono">{{ $payment->currency }}{{ number_format($payment->amount, 2) }}</span>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-lg border border-slate-200">
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Payment Method</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $payment->payment_method_name }}</span>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-lg border border-slate-200">
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Transaction ID (TID)</span>
                        <span class="font-mono font-bold text-blue-700 text-sm uppercase">{{ $payment->transaction_id }}</span>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-lg border border-slate-200">
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Payment Date</span>
                        <span class="font-bold text-slate-900">{{ date('l, M j, Y', strtotime($payment->payment_date)) }}</span>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-lg border border-slate-200">
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Sender Account Name</span>
                        <span class="font-bold text-slate-900">{{ $payment->sender_name }}</span>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-lg border border-slate-200">
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Sender Account Number / Phone</span>
                        <span class="font-mono font-bold text-slate-800">{{ $payment->sender_account }}</span>
                    </div>
                </div>

                @if($payment->admin_notes)
                <div class="mt-4 p-3.5 rounded-lg border {{ $payment->isRejected() ? 'bg-red-50 border-red-200 text-red-950' : 'bg-slate-50 border-slate-200 text-slate-800' }}">
                    <span class="text-[10px] uppercase font-bold block mb-1 {{ $payment->isRejected() ? 'text-red-900' : 'text-slate-500' }}">
                        Administrative Notes
                    </span>
                    <p class="leading-relaxed">{{ $payment->admin_notes }}</p>
                </div>
                @endif
            </div>

            <!-- Associated Appointment Information -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4 text-xs">
                <h3 class="font-extrabold text-sm text-slate-900 pb-3 border-b border-slate-150 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-blue-600"></i> Associated Appointment Details
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Appointment Number</span>
                        <span class="font-mono font-bold text-blue-700">{{ $payment->appointment->appointment_number }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Appointment Status</span>
                        <span class="font-bold uppercase text-[11px] {{ $payment->appointment->status === 'confirmed' ? 'text-emerald-600' : ($payment->appointment->status === 'pending' ? 'text-amber-600' : 'text-slate-700') }}">
                            {{ $payment->appointment->status }}
                        </span>
                    </div>

                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Assigned Lawyer</span>
                        <span class="font-bold text-slate-900">{{ $payment->appointment->lawyer->name ?? 'Lawyer' }}</span>
                        <span class="text-slate-500 text-[11px] block">{{ $payment->appointment->lawyer->email }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Customer Client</span>
                        <span class="font-bold text-slate-900">{{ $payment->customer->name }}</span>
                        <span class="text-slate-500 text-[11px] block">{{ $payment->customer->email }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Legal Practice Area</span>
                        <span class="font-semibold text-slate-800">{{ $payment->appointment->service->name ?? 'General' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Schedule Slot</span>
                        <span class="font-bold text-slate-800">{{ date('M j, Y', strtotime($payment->appointment->appointment_date)) }}</span>
                        <span class="text-slate-500 text-[11px] block">{{ date('g:i A', strtotime($payment->appointment->start_time)) }} - {{ date('g:i A', strtotime($payment->appointment->end_time)) }}</span>
                    </div>
                </div>
            </div>

            <!-- Verification Action Box if Pending -->
            @if($payment->isPending())
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
                <h3 class="font-extrabold text-sm text-slate-900 pb-3 border-b border-slate-150 flex items-center gap-2">
                    <i class="fa-solid fa-stamp text-purple-600"></i> Perform Administrative Verification
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Verify Form -->
                    <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="bg-emerald-50/70 border border-emerald-200 p-4 rounded-xl space-y-3">
                        @csrf
                        <div class="font-bold text-xs text-emerald-950 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Approve & Generate Receipt
                        </div>
                        <p class="text-[11px] text-emerald-900">Marks payment as verified, confirms appointment, and assigns unique receipt number.</p>
                        
                        <div>
                            <input type="text" name="admin_notes" value="Payment verified and approved by Administrator."
                                class="w-full px-3 py-1.5 text-xs border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-600" placeholder="Verification note">
                        </div>

                        <label class="flex items-center gap-1.5 text-[11px] text-emerald-950">
                            <input type="checkbox" name="auto_confirm_appointment" value="1" checked class="rounded border-emerald-300 text-emerald-600">
                            <span>Confirm appointment slot</span>
                        </label>

                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-2 rounded-lg transition shadow-sm">
                            Confirm Verification
                        </button>
                    </form>

                    <!-- Reject Form -->
                    <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST" class="bg-red-50/70 border border-red-200 p-4 rounded-xl space-y-3">
                        @csrf
                        <div class="font-bold text-xs text-red-950 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-xmark text-red-600"></i> Reject Submission
                        </div>
                        <p class="text-[11px] text-red-900">Provide rejection reason visible to the customer so they can re-submit.</p>

                        <div>
                            <textarea name="admin_notes" rows="2" required placeholder="State exact rejection reason..."
                                class="w-full px-3 py-1.5 text-xs border border-red-300 rounded-lg focus:ring-2 focus:ring-red-600"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-xs py-2 rounded-lg transition shadow-sm">
                            Reject Payment
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>

        <!-- Right: Payment Proof Document -->
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm text-xs space-y-4">
                <h3 class="font-extrabold text-sm text-slate-900 pb-3 border-b border-slate-150 flex items-center gap-2">
                    <i class="fa-solid fa-image text-blue-600"></i> Payment Proof Attachment
                </h3>

                @if($payment->payment_proof)
                    <div class="bg-slate-900 rounded-xl p-2 text-center">
                        <a href="{{ route('customer.payment.proof', $payment->id) }}" target="_blank" class="block group">
                            <img src="{{ route('customer.payment.proof', $payment->id) }}" alt="Proof Document" class="max-h-72 mx-auto rounded-lg object-contain group-hover:opacity-90 transition">
                        </a>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('customer.payment.proof', $payment->id) }}" target="_blank" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs py-2 px-3 rounded-lg border border-slate-300 transition inline-flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Open High-Res Image
                        </a>
                    </div>
                @else
                    <div class="p-8 text-center text-slate-400 bg-slate-50 rounded-xl border border-slate-200">
                        No payment screenshot attached.
                    </div>
                @endif
            </div>

            <!-- Audit Trail -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm text-xs space-y-3">
                <h3 class="font-extrabold text-sm text-slate-900 pb-2 border-b border-slate-150 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-slate-600"></i> Audit Information
                </h3>

                <div class="flex justify-between py-1">
                    <span class="text-slate-400">Created:</span>
                    <span class="font-semibold text-slate-800">{{ $payment->created_at->format('M j, Y g:i A') }}</span>
                </div>

                @if($payment->verified_at)
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">Audited At:</span>
                    <span class="font-semibold text-slate-800">{{ $payment->verified_at->format('M j, Y g:i A') }}</span>
                </div>

                <div class="flex justify-between py-1">
                    <span class="text-slate-400">Auditor:</span>
                    <span class="font-bold text-slate-900">{{ $payment->verifier->name ?? 'Administrator' }}</span>
                </div>
                @endif

                @if($payment->receipt_number)
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">Receipt Code:</span>
                    <span class="font-mono font-bold text-blue-700">{{ $payment->receipt_number }}</span>
                </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
