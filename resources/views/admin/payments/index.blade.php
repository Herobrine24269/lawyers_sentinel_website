@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Manual Payment Verification & Reconciliation</h1>
            <p class="text-xs text-slate-500 mt-1">Audit customer manual payment transfers via Easypaisa, JazzCash & Bank Transfer, verify receipts, and issue confirmations</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.payment-settings') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs px-4 py-2 rounded-lg border border-slate-300 transition flex items-center gap-1.5">
                <i class="fa-solid fa-sliders text-blue-600"></i> Payment Gateway Settings
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Payments</span>
            <span class="text-2xl font-extrabold text-navy-900">{{ $totalPaymentsCount }}</span>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <span class="text-xs font-bold text-amber-600 uppercase tracking-wider block mb-1">Pending Verification</span>
            <span class="text-2xl font-extrabold text-amber-600">{{ $pendingCount }}</span>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider block mb-1">Verified Payments</span>
            <span class="text-2xl font-extrabold text-emerald-600">{{ $verifiedCount }}</span>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">Total Verified Revenue</span>
            <span class="text-2xl font-extrabold text-blue-600">${{ number_format($totalRevenue, 2) }}</span>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <form action="{{ route('admin.payments') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 items-end text-xs">
            
            <!-- Search Keyword -->
            <div class="lg:col-span-2">
                <label class="block font-bold text-slate-700 mb-1">Search Keywords</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Receipt #, TID, Appt #, Customer name or email..."
                        class="w-full pl-8 pr-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600">
                    <i class="fa-solid fa-magnifying-glass text-slate-400 absolute left-2.5 top-2.5"></i>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Payment Status</label>
                <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs bg-white focus:ring-2 focus:ring-blue-600">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Verification</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified & Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Payment Method Filter -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Payment Method</label>
                <select name="method" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs bg-white focus:ring-2 focus:ring-blue-600">
                    <option value="">All Methods</option>
                    <option value="easypaisa" {{ request('method') === 'easypaisa' ? 'selected' : '' }}>Easypaisa</option>
                    <option value="jazzcash" {{ request('method') === 'jazzcash' ? 'selected' : '' }}>JazzCash</option>
                    <option value="bank_transfer" {{ request('method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                </select>
            </div>

            <!-- Filter Submit & Reset -->
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-navy-900 text-white font-bold py-2 px-4 rounded-lg transition">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'method', 'date_from', 'date_to']))
                <a href="{{ route('admin.payments') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2 px-3 rounded-lg transition" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Payments Data Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-150 font-bold text-sm text-slate-900 flex justify-between items-center">
            <span>Customer Payment Submissions ({{ $payments->total() }})</span>
            <span class="text-xs text-slate-500 font-normal">Real-time audit log</span>
        </div>

        @if($payments->isEmpty())
            <div class="p-12 text-center text-xs text-slate-500">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2 text-lg">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                No payment submissions match your search/filter criteria.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5">Payment / Receipt #</th>
                            <th class="px-5 py-3.5">Appt Code</th>
                            <th class="px-5 py-3.5">Customer</th>
                            <th class="px-5 py-3.5">Assigned Lawyer</th>
                            <th class="px-5 py-3.5">Method & TID</th>
                            <th class="px-5 py-3.5">Amount</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-center">Proof</th>
                            <th class="px-5 py-3.5 text-right">Verification Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150 font-medium">
                        @foreach($payments as $payment)
                        <tr class="hover:bg-slate-50/80 transition">
                            
                            <!-- Payment / Receipt # -->
                            <td class="px-5 py-4">
                                @if($payment->receipt_number)
                                    <span class="font-mono font-bold text-blue-700 text-xs block">{{ $payment->receipt_number }}</span>
                                @else
                                    <span class="font-mono font-bold text-slate-600 text-xs block">PAY-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</span>
                                @endif
                                <span class="text-[10px] text-slate-400">{{ $payment->created_at->format('M j, Y g:i A') }}</span>
                            </td>

                            <!-- Appt Code -->
                            <td class="px-5 py-4">
                                <span class="font-mono font-bold text-slate-900 block">{{ $payment->appointment->appointment_number }}</span>
                                <span class="text-[10px] text-slate-500">{{ $payment->appointment->service->name ?? 'Service' }}</span>
                            </td>

                            <!-- Customer -->
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">{{ $payment->customer->name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $payment->customer->email }}</div>
                            </td>

                            <!-- Lawyer -->
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">{{ $payment->appointment->lawyer->name ?? 'Lawyer' }}</div>
                                <div class="text-[10px] text-slate-500">{{ date('M j, Y', strtotime($payment->appointment->appointment_date)) }}</div>
                            </td>

                            <!-- Method & TID -->
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900 flex items-center gap-1">
                                    @if($payment->payment_method === 'easypaisa')
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> Easypaisa
                                    @elseif($payment->payment_method === 'jazzcash')
                                        <span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span> JazzCash
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span> Bank Wire
                                    @endif
                                </div>
                                <div class="font-mono text-[11px] text-blue-700 font-bold uppercase">{{ $payment->transaction_id }}</div>
                                <div class="text-[10px] text-slate-400">Sender: {{ $payment->sender_account }} ({{ $payment->sender_name }})</div>
                            </td>

                            <!-- Amount -->
                            <td class="px-5 py-4">
                                <span class="font-extrabold text-navy-900 font-mono text-sm block">
                                    {{ $payment->currency }}{{ number_format($payment->amount, 2) }}
                                </span>
                                <span class="text-[10px] text-slate-400">Paid: {{ date('M j', strtotime($payment->payment_date)) }}</span>
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-4">
                                @if($payment->isVerified())
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-emerald-300 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check text-[8px]"></i> Verified
                                    </span>
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ $payment->verified_at ? $payment->verified_at->format('M j') : '' }}</div>
                                @elseif($payment->isPending())
                                    <span class="bg-amber-100 text-amber-900 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-amber-300 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-clock text-[8px]"></i> Pending
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-red-300 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-xmark text-[8px]"></i> Rejected
                                    </span>
                                @endif
                            </td>

                            <!-- Proof Thumbnail -->
                            <td class="px-5 py-4 text-center">
                                @if($payment->payment_proof)
                                    <button type="button" onclick="openProofModal('{{ route('customer.payment.proof', $payment->id) }}', '{{ $payment->transaction_id }}')"
                                        class="inline-block relative group" title="Click to view payment proof">
                                        <img src="{{ route('customer.payment.proof', $payment->id) }}" alt="Proof" class="w-10 h-10 rounded-lg object-cover border border-slate-300 shadow-sm group-hover:scale-105 transition">
                                        <span class="absolute inset-0 bg-black/30 rounded-lg opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs transition">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </span>
                                    </button>
                                @else
                                    <span class="text-slate-400 text-xs">--</span>
                                @endif
                            </td>

                            <!-- Verification Actions -->
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 text-[11px] font-bold px-2 py-1 rounded border border-slate-300 transition" title="View Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    @if($payment->isPending())
                                        <!-- Verify Action Button -->
                                        <button type="button" onclick="openVerifyModal('{{ $payment->id }}', '{{ $payment->transaction_id }}', '{{ $payment->customer->name }}', '{{ $payment->currency }}{{ number_format($payment->amount, 2) }}')"
                                            class="bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold px-2.5 py-1 rounded transition flex items-center gap-1 shadow-sm">
                                            <i class="fa-solid fa-check"></i> Verify
                                        </button>

                                        <!-- Reject Action Button -->
                                        <button type="button" onclick="openRejectModal('{{ $payment->id }}', '{{ $payment->transaction_id }}', '{{ $payment->customer->name }}')"
                                            class="bg-red-600 hover:bg-red-700 text-white text-[11px] font-bold px-2.5 py-1 rounded transition flex items-center gap-1 shadow-sm">
                                            <i class="fa-solid fa-xmark"></i> Reject
                                        </button>
                                    @elseif($payment->isVerified())
                                        <a href="{{ route('customer.payment.receipt', $payment->id) }}" target="_blank" class="bg-navy-900 hover:bg-blue-600 text-white text-[11px] font-bold px-2 py-1 rounded transition" title="View Official Receipt">
                                            <i class="fa-solid fa-receipt"></i>
                                        </a>
                                        <a href="{{ route('customer.payment.receipt.download', $payment->id) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold px-2 py-1 rounded transition" title="Download PDF">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                    @endif

                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-200">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Proof Image Preview Modal -->
<div id="proofModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto animate-fade-in">
        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
            <div>
                <h3 class="font-extrabold text-sm text-navy-900">Payment Screenshot Proof</h3>
                <p class="text-[11px] text-slate-500">Transaction ID: <span id="proofModalTid" class="font-mono font-bold text-blue-600"></span></p>
            </div>
            <button type="button" onclick="closeProofModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="text-center bg-slate-900 rounded-xl p-2">
            <img id="proofModalImg" src="" alt="Proof Document" class="max-h-[60vh] mx-auto rounded-lg object-contain">
        </div>

        <div class="flex justify-end">
            <button type="button" onclick="closeProofModal()" class="bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold text-xs px-4 py-2 rounded-lg transition">
                Close Preview
            </button>
        </div>
    </div>
</div>

<!-- Verify Payment Modal -->
<div id="verifyModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-5 animate-fade-in">
        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-stamp"></i>
                </span>
                <div>
                    <h3 class="font-extrabold text-sm text-navy-900">Approve & Verify Payment</h3>
                    <p class="text-[11px] text-slate-500">Reconcile transaction and generate official receipt</p>
                </div>
            </div>
            <button type="button" onclick="closeVerifyModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="verifyForm" action="" method="POST" class="space-y-4 text-xs">
            @csrf
            
            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-1.5">
                <div class="flex justify-between">
                    <span class="text-slate-500">Customer:</span>
                    <strong id="verifyModalCustomer" class="text-slate-900"></strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Transaction ID:</span>
                    <strong id="verifyModalTid" class="font-mono text-blue-700"></strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Verified Amount:</span>
                    <strong id="verifyModalAmount" class="text-emerald-700 font-extrabold text-sm"></strong>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Administrative Notes / Reference</label>
                <textarea name="admin_notes" rows="2" placeholder="e.g. Bank statement match verified. Transaction confirmed."
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-emerald-600">Payment verified and approved by Administrator.</textarea>
            </div>

            <label class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 p-3 rounded-xl cursor-pointer">
                <input type="checkbox" name="auto_confirm_appointment" value="1" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span class="text-emerald-950 font-semibold text-[11px]">Automatically confirm associated appointment slot</span>
            </label>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-150">
                <button type="button" onclick="closeVerifyModal()" class="bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold px-4 py-2 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold px-5 py-2 rounded-lg transition flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-circle-check"></i> Confirm Verification
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Payment Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-5 animate-fade-in">
        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-red-100 text-red-700 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </span>
                <div>
                    <h3 class="font-extrabold text-sm text-red-900">Reject Payment Submission</h3>
                    <p class="text-[11px] text-slate-500">Provide rejection reason for customer review</p>
                </div>
            </div>
            <button type="button" onclick="closeRejectModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="rejectForm" action="" method="POST" class="space-y-4 text-xs">
            @csrf
            
            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-1.5">
                <div class="flex justify-between">
                    <span class="text-slate-500">Customer:</span>
                    <strong id="rejectModalCustomer" class="text-slate-900"></strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Transaction ID:</span>
                    <strong id="rejectModalTid" class="font-mono text-slate-800"></strong>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">
                    Rejection Reason / Notes <span class="text-red-500">*</span>
                </label>
                <textarea name="admin_notes" rows="3" required placeholder="State exact reason (e.g. Transaction ID TID-XXXXX not found in bank ledger / screenshot unreadable / amount mismatch)..."
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-red-600"></textarea>
                <span class="text-[10px] text-slate-400 block mt-1">This feedback will be displayed directly to the customer on their dashboard.</span>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-150">
                <button type="button" onclick="closeRejectModal()" class="bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold px-4 py-2 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-extrabold px-5 py-2 rounded-lg transition flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-xmark"></i> Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openProofModal(imgSrc, tid) {
        document.getElementById('proofModalImg').src = imgSrc;
        document.getElementById('proofModalTid').textContent = tid;
        document.getElementById('proofModal').classList.remove('hidden');
    }

    function closeProofModal() {
        document.getElementById('proofModal').classList.add('hidden');
    }

    function openVerifyModal(paymentId, tid, customer, amount) {
        document.getElementById('verifyForm').action = '/admin/payments/' + paymentId + '/verify';
        document.getElementById('verifyModalTid').textContent = tid;
        document.getElementById('verifyModalCustomer').textContent = customer;
        document.getElementById('verifyModalAmount').textContent = amount;
        document.getElementById('verifyModal').classList.remove('hidden');
    }

    function closeVerifyModal() {
        document.getElementById('verifyModal').classList.add('hidden');
    }

    function openRejectModal(paymentId, tid, customer) {
        document.getElementById('rejectForm').action = '/admin/payments/' + paymentId + '/reject';
        document.getElementById('rejectModalTid').textContent = tid;
        document.getElementById('rejectModalCustomer').textContent = customer;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection
