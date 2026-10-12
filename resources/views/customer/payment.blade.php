@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

    <!-- Breadcrumb & Title -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-2">
            <a href="{{ route('customer.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-slate-800">Appointment Payment</span>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-navy-900 tracking-tight">Complete Consultation Payment</h1>
                <p class="text-xs text-slate-500 mt-1">Submit your manual payment verification details for appointment #<span class="font-mono font-bold text-blue-600">{{ $appointment->appointment_number }}</span></p>
            </div>
            <span class="bg-amber-100 text-amber-900 text-xs font-bold px-3 py-1.5 rounded-lg border border-amber-300 inline-flex items-center gap-1.5 self-start sm:self-auto">
                <i class="fa-solid fa-clock-rotate-left"></i> Awaiting Payment Submission
            </span>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 text-xs p-4 rounded-xl mb-6 flex items-center gap-2">
        <i class="fa-solid fa-triangle-exclamation text-red-600 text-sm"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 text-xs p-4 rounded-xl mb-6">
        <div class="font-bold flex items-center gap-2 mb-1">
            <i class="fa-solid fa-circle-exclamation text-red-600"></i> Please correct the following errors:
        </div>
        <ul class="list-disc list-inside space-y-0.5 text-slate-700 ml-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Appointment Summary & Breakdown -->
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="font-extrabold text-sm text-navy-900 pb-3 border-b border-slate-150 flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice-dollar text-blue-600"></i> Appointment Summary
                </h3>

                <div class="mt-4 space-y-3.5 text-xs">
                    <div>
                        <span class="text-slate-400 font-medium block text-[11px]">Appointment Code</span>
                        <span class="font-mono font-bold text-blue-700">{{ $appointment->appointment_number }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block text-[11px]">Assigned Attorney</span>
                        <span class="font-bold text-navy-900 block">{{ $appointment->lawyer->name ?? 'Lawyer' }}</span>
                        <span class="text-slate-500 text-[11px]">{{ $appointment->lawyer->lawyerProfile->location ?? 'City' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block text-[11px]">Legal Matter / Service</span>
                        <span class="bg-slate-100 text-slate-800 px-2 py-0.5 rounded font-semibold text-[11px] border border-slate-200 inline-block mt-0.5">
                            {{ $appointment->service->name ?? 'General Legal Counsel' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block text-[11px]">Consultation Schedule</span>
                        <div class="font-bold text-slate-800">{{ date('l, M j, Y', strtotime($appointment->appointment_date)) }}</div>
                        <div class="text-slate-600 text-[11px]"><i class="fa-solid fa-clock text-amber-500 mr-1"></i> {{ date('g:i A', strtotime($appointment->start_time)) }} - {{ date('g:i A', strtotime($appointment->end_time)) }}</div>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block text-[11px]">Format / Venue</span>
                        <span class="font-semibold text-slate-700">{{ $appointment->meeting_place }}</span>
                    </div>
                </div>

                <!-- Fee Box -->
                <div class="mt-6 pt-4 border-t border-slate-200 bg-navy-950 text-white p-4 rounded-xl">
                    <div class="flex items-center justify-between text-xs text-slate-300">
                        <span>Consultation Fee</span>
                        <span class="font-bold text-slate-200">{{ $currency }}{{ number_format($payableAmount, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-slate-300 mt-1">
                        <span>Platform Processing</span>
                        <span class="text-emerald-400 font-semibold">FREE</span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-800 flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Total Payable</span>
                        <span class="text-xl font-extrabold text-amber-400">{{ $currency }}{{ number_format($payableAmount, 2) }}</span>
                    </div>
                </div>

                <div class="mt-4 text-[11px] text-slate-500 flex items-start gap-2 bg-slate-50 p-3 rounded-lg border border-slate-200">
                    <i class="fa-solid fa-shield-halved text-blue-600 mt-0.5"></i>
                    <span>All payments undergo secure manual reconciliation before attorney appointment confirmation.</span>
                </div>
            </div>

            @if($existingPayment && $existingPayment->isRejected())
            <div class="bg-red-50 border border-red-200 p-4 rounded-xl text-xs">
                <div class="font-bold text-red-900 flex items-center gap-1.5 mb-1">
                    <i class="fa-solid fa-circle-exclamation text-red-600"></i> Previous Attempt Rejected
                </div>
                <p class="text-red-800 mb-2">Reason: {{ $existingPayment->admin_notes ?: 'Payment could not be verified in records.' }}</p>
                <p class="text-slate-600 text-[11px]">Please review the instructions, verify your transaction ID/receipt, and re-submit below.</p>
            </div>
            @endif

        </div>

        <!-- Right: Payment Method & Submission Form -->
        <div class="lg:col-span-2 space-y-6">
            
            <form action="{{ route('customer.payment.store', $appointment->id) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                @csrf

                <!-- Section 1: Choose Method -->
                <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-7 shadow-sm mb-6">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-150">
                        <span class="w-6 h-6 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center">1</span>
                        <h2 class="font-extrabold text-sm text-navy-900">Select Manual Payment Method</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5" id="methodSelectors">
                        
                        <!-- Easypaisa -->
                        @php $ep = $paymentSettings['easypaisa'] ?? null; @endphp
                        @if($ep && $ep->is_active)
                        <label class="relative border-2 rounded-xl p-4 cursor-pointer transition flex flex-col justify-between hover:border-emerald-500 hover:bg-emerald-50/30 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40 has-[:checked]:ring-2 has-[:checked]:ring-emerald-500/30">
                            <input type="radio" name="payment_method" value="easypaisa" class="sr-only" {{ old('payment_method', 'easypaisa') === 'easypaisa' ? 'checked' : '' }} onchange="switchMethod('easypaisa')">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-extrabold text-xs">
                                        <i class="fa-solid fa-mobile-screen-button"></i>
                                    </span>
                                    <span class="text-[10px] font-bold bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">Active</span>
                                </div>
                                <span class="font-extrabold text-xs text-navy-900 block">Easypaisa</span>
                                <span class="text-[11px] text-slate-500">Mobile Wallet Transfer</span>
                            </div>
                        </label>
                        @endif

                        <!-- JazzCash -->
                        @php $jc = $paymentSettings['jazzcash'] ?? null; @endphp
                        @if($jc && $jc->is_active)
                        <label class="relative border-2 rounded-xl p-4 cursor-pointer transition flex flex-col justify-between hover:border-amber-500 hover:bg-amber-50/30 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50/40 has-[:checked]:ring-2 has-[:checked]:ring-amber-500/30">
                            <input type="radio" name="payment_method" value="jazzcash" class="sr-only" {{ old('payment_method') === 'jazzcash' ? 'checked' : '' }} onchange="switchMethod('jazzcash')">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center font-extrabold text-xs">
                                        <i class="fa-solid fa-wallet"></i>
                                    </span>
                                    <span class="text-[10px] font-bold bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full">Active</span>
                                </div>
                                <span class="font-extrabold text-xs text-navy-900 block">JazzCash</span>
                                <span class="text-[11px] text-slate-500">Mobile Account Transfer</span>
                            </div>
                        </label>
                        @endif

                        <!-- Bank Transfer -->
                        @php $bt = $paymentSettings['bank_transfer'] ?? null; @endphp
                        @if($bt && $bt->is_active)
                        <label class="relative border-2 rounded-xl p-4 cursor-pointer transition flex flex-col justify-between hover:border-blue-500 hover:bg-blue-50/30 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/40 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/30">
                            <input type="radio" name="payment_method" value="bank_transfer" class="sr-only" {{ old('payment_method') === 'bank_transfer' ? 'checked' : '' }} onchange="switchMethod('bank_transfer')">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-extrabold text-xs">
                                        <i class="fa-solid fa-building-columns"></i>
                                    </span>
                                    <span class="text-[10px] font-bold bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Active</span>
                                </div>
                                <span class="font-extrabold text-xs text-navy-900 block">Bank Transfer</span>
                                <span class="text-[11px] text-slate-500">Online Net Banking / Wire</span>
                            </div>
                        </label>
                        @endif

                    </div>

                    <!-- Dynamic Instructions Panel -->
                    <div class="mt-6">
                        
                        <!-- Easypaisa Instructions Box -->
                        <div id="box-easypaisa" class="method-box bg-slate-50 border border-slate-200 rounded-xl p-5 space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-circle-info text-emerald-600 text-sm"></i>
                                    <span class="font-bold text-xs text-navy-900">Easypaisa Beneficiary Details</span>
                                </div>
                                <span class="text-[11px] font-mono text-slate-500">Payable: <strong class="text-emerald-700">{{ $currency }}{{ number_format($payableAmount, 2) }}</strong></span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div class="bg-white p-3 rounded-lg border border-slate-200">
                                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Account Title</span>
                                    <span class="font-bold text-navy-900 text-sm">{{ $paymentSettings['easypaisa']->account_title ?? 'Juris Sentinel Legal' }}</span>
                                </div>
                                <div class="bg-white p-3 rounded-lg border border-slate-200">
                                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Account / Mobile Number</span>
                                    <span class="font-mono font-bold text-emerald-600 text-sm">{{ $paymentSettings['easypaisa']->account_number ?? '0300-1234567' }}</span>
                                </div>
                            </div>

                            <div class="bg-emerald-50/80 border border-emerald-200 text-emerald-950 p-3.5 rounded-lg text-xs leading-relaxed whitespace-pre-line">
                                <strong class="font-bold block mb-1 text-emerald-900"><i class="fa-solid fa-list-check mr-1"></i> Payment Instructions:</strong>
                                {{ $paymentSettings['easypaisa']->instructions ?? "1. Send money via Easypaisa to the account number above.\n2. Save the transaction ID and screenshot." }}
                            </div>
                        </div>

                        <!-- JazzCash Instructions Box -->
                        <div id="box-jazzcash" class="method-box hidden bg-slate-50 border border-slate-200 rounded-xl p-5 space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-circle-info text-amber-600 text-sm"></i>
                                    <span class="font-bold text-xs text-navy-900">JazzCash Beneficiary Details</span>
                                </div>
                                <span class="text-[11px] font-mono text-slate-500">Payable: <strong class="text-amber-700">{{ $currency }}{{ number_format($payableAmount, 2) }}</strong></span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div class="bg-white p-3 rounded-lg border border-slate-200">
                                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Account Title</span>
                                    <span class="font-bold text-navy-900 text-sm">{{ $paymentSettings['jazzcash']->account_title ?? 'Juris Sentinel Legal' }}</span>
                                </div>
                                <div class="bg-white p-3 rounded-lg border border-slate-200">
                                    <span class="text-slate-400 text-[10px] uppercase font-bold block">JazzCash Mobile Number</span>
                                    <span class="font-mono font-bold text-amber-600 text-sm">{{ $paymentSettings['jazzcash']->account_number ?? '0301-7654321' }}</span>
                                </div>
                            </div>

                            <div class="bg-amber-50/80 border border-amber-200 text-amber-950 p-3.5 rounded-lg text-xs leading-relaxed whitespace-pre-line">
                                <strong class="font-bold block mb-1 text-amber-900"><i class="fa-solid fa-list-check mr-1"></i> Payment Instructions:</strong>
                                {{ $paymentSettings['jazzcash']->instructions ?? "1. Send money via JazzCash to the account number above.\n2. Save the transaction ID and screenshot." }}
                            </div>
                        </div>

                        <!-- Bank Transfer Instructions Box -->
                        <div id="box-bank_transfer" class="method-box hidden bg-slate-50 border border-slate-200 rounded-xl p-5 space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-circle-info text-blue-600 text-sm"></i>
                                    <span class="font-bold text-xs text-navy-900">Bank Transfer Beneficiary Details</span>
                                </div>
                                <span class="text-[11px] font-mono text-slate-500">Payable: <strong class="text-blue-700">{{ $currency }}{{ number_format($payableAmount, 2) }}</strong></span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                                <div class="bg-white p-3 rounded-lg border border-slate-200">
                                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Bank Name</span>
                                    <span class="font-bold text-navy-900">{{ $paymentSettings['bank_transfer']->bank_name ?? 'Standard Chartered / HBL' }}</span>
                                </div>
                                <div class="bg-white p-3 rounded-lg border border-slate-200">
                                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Account Title</span>
                                    <span class="font-bold text-navy-900">{{ $paymentSettings['bank_transfer']->account_title ?? 'Juris Sentinel Legal Portal PVT' }}</span>
                                </div>
                                <div class="bg-white p-3 rounded-lg border border-slate-200">
                                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Account Number</span>
                                    <span class="font-mono font-bold text-blue-700">{{ $paymentSettings['bank_transfer']->account_number ?? '0102938475610023' }}</span>
                                </div>
                                <div class="bg-white p-3 rounded-lg border border-slate-200">
                                    <span class="text-slate-400 text-[10px] uppercase font-bold block">IBAN Number</span>
                                    <span class="font-mono font-bold text-slate-800 text-[11px]">{{ $paymentSettings['bank_transfer']->iban ?? 'PK36SCBL0000001029384756' }}</span>
                                </div>
                                @if(!empty($paymentSettings['bank_transfer']->branch_code))
                                <div class="bg-white p-3 rounded-lg border border-slate-200 sm:col-span-2">
                                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Branch / Details</span>
                                    <span class="font-semibold text-slate-700">{{ $paymentSettings['bank_transfer']->branch_code }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="bg-blue-50/80 border border-blue-200 text-blue-950 p-3.5 rounded-lg text-xs leading-relaxed whitespace-pre-line">
                                <strong class="font-bold block mb-1 text-blue-900"><i class="fa-solid fa-list-check mr-1"></i> Payment Instructions:</strong>
                                {{ $paymentSettings['bank_transfer']->instructions ?? "1. Transfer funds via Online Net Banking, ATM, or mobile app.\n2. Save reference ID and transaction receipt screenshot." }}
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Section 2: Enter Transaction Details -->
                <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-7 shadow-sm mb-6">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-150">
                        <span class="w-6 h-6 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center">2</span>
                        <h2 class="font-extrabold text-sm text-navy-900">Enter Your Transaction Details</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Sender Account Name / Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="sender_name" value="{{ old('sender_name', auth()->user()->name) }}" required
                                placeholder="e.g. John Doe"
                                class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                            <span class="text-[11px] text-slate-400 mt-1 block">Account holder name you sent money from</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1" id="lbl_sender_account">
                                Sender Mobile / Account Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="sender_account" value="{{ old('sender_account', auth()->user()->phone) }}" required
                                placeholder="e.g. 03XX-XXXXXXX or Account #"
                                class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 font-mono">
                            <span class="text-[11px] text-slate-400 mt-1 block">Your mobile wallet or bank account number</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Transaction / Reference ID (TID) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="transaction_id" value="{{ old('transaction_id') }}" required
                                placeholder="e.g. TID-982187312 or TRX78129031"
                                class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 font-mono uppercase">
                            <span class="text-[11px] text-slate-400 mt-1 block">Official TID received via SMS or app receipt</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Payment Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required
                                class="w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                            <span class="text-[11px] text-slate-400 mt-1 block">Date on which payment was transferred</span>
                        </div>

                    </div>
                </div>

                <!-- Section 3: Upload Proof -->
                <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-7 shadow-sm mb-6">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-150">
                        <span class="w-6 h-6 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center">3</span>
                        <h2 class="font-extrabold text-sm text-navy-900">Upload Payment Proof / Screenshot</h2>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">
                            Attach Transfer Screenshot or Bank Slip <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="border-2 border-dashed border-slate-300 hover:border-blue-500 rounded-xl p-6 text-center transition bg-slate-50/50 relative cursor-pointer" onclick="document.getElementById('proof_input').click()">
                            <input type="file" name="payment_proof" id="proof_input" accept="image/jpeg,image/png,image/jpg,image/webp" required class="hidden" onchange="previewProofImage(event)">
                            
                            <div id="upload_prompt" class="space-y-2">
                                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto text-xl">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <div class="text-xs text-slate-700 font-bold">
                                    Click here to upload payment screenshot / receipt
                                </div>
                                <p class="text-[11px] text-slate-400">Supported formats: JPG, JPEG, PNG, WEBP (Max size: 5MB)</p>
                            </div>

                            <div id="preview_container" class="hidden space-y-3">
                                <img id="image_preview" src="" alt="Proof Preview" class="max-h-56 mx-auto rounded-lg border border-slate-200 shadow-sm object-contain">
                                <div class="text-xs text-emerald-700 font-bold flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-circle-check"></i> <span id="file_name_label">Image selected</span>
                                </div>
                                <button type="button" onclick="event.stopPropagation(); clearProofImage();" class="text-xs text-red-600 hover:underline font-semibold">
                                    <i class="fa-solid fa-trash-can mr-1"></i> Choose different file
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Section 4: Submit Actions -->
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-slate-500 text-center sm:text-left">
                        <i class="fa-solid fa-circle-info text-blue-600 mr-1"></i> By submitting, you confirm that you have transferred the exact amount of <strong class="text-slate-800">{{ $currency }}{{ number_format($payableAmount, 2) }}</strong>.
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('customer.dashboard') }}" class="w-1/2 sm:w-auto text-center px-4 py-3 border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl transition">
                            Cancel
                        </a>
                        <button type="submit" id="btnSubmitPayment" class="w-1/2 sm:w-auto bg-blue-600 hover:bg-navy-900 text-white font-extrabold text-xs px-6 py-3 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Submit Payment Proof
                        </button>
                    </div>
                </div>

            </form>

        </div>

    </div>

</div>

<script>
    function switchMethod(method) {
        document.querySelectorAll('.method-box').forEach(el => el.classList.add('hidden'));
        const target = document.getElementById('box-' + method);
        if (target) {
            target.classList.remove('hidden');
        }

        const lbl = document.getElementById('lbl_sender_account');
        if (method === 'bank_transfer') {
            lbl.innerHTML = 'Sender Bank & Account / IBAN <span class="text-red-500">*</span>';
        } else {
            lbl.innerHTML = 'Sender ' + (method === 'easypaisa' ? 'Easypaisa' : 'JazzCash') + ' Mobile Number <span class="text-red-500">*</span>';
        }
    }

    function previewProofImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('image_preview').src = e.target.result;
            document.getElementById('file_name_label').textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            document.getElementById('upload_prompt').classList.add('hidden');
            document.getElementById('preview_container').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    function clearProofImage() {
        document.getElementById('proof_input').value = '';
        document.getElementById('preview_container').classList.add('hidden');
        document.getElementById('upload_prompt').classList.remove('hidden');
    }

    // Initialize initial active method
    document.addEventListener('DOMContentLoaded', () => {
        const checked = document.querySelector('input[name="payment_method"]:checked');
        if (checked) {
            switchMethod(checked.value);
        }
    });
</script>
@endsection
