@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

    <!-- Customer Header -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <img src="{{ $user->profile_pic_url }}" alt="{{ $user->name }}"
                class="w-16 h-16 rounded-xl object-cover border-2 border-blue-500 shadow-sm flex-shrink-0">
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-2xl font-extrabold text-navy-900">Welcome, {{ $user->name }}</h1>
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded">Customer Portal</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Manage your lawyer consultation appointments, schedule slots & personal profile</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="document.getElementById('customer_profile_modal').classList.toggle('hidden')" class="bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold px-4 py-2.5 rounded-lg border border-slate-300 transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-user-pen"></i> Edit Profile & Picture
            </button>
            <a href="{{ route('lawyers.index') }}" class="bg-navy-900 hover:bg-blue-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-magnifying-glass"></i> Book New Lawyer Slot
            </a>
        </div>
    </div>

    <!-- Customer Profile Update Card (Collapsible) -->
    <div id="customer_profile_modal" class="hidden bg-white rounded-xl border border-blue-200 p-6 mb-8 shadow-md transition animate-fade-in">
        <h2 class="font-extrabold text-base text-navy-900 mb-1 flex items-center gap-2">
            <i class="fa-solid fa-id-card text-blue-600"></i> Update Customer Profile & Picture
        </h2>
        <p class="text-xs text-slate-500 mb-4">You can update your display name, phone number, and optional profile picture below</p>
        <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600"
                        placeholder="+1 (555) 000-0000">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        Profile Picture <span class="text-[10px] text-slate-500">(Optional / اختیاری)</span>
                    </label>
                    <input type="file" name="profile_pic" accept="image/*"
                        class="w-full text-xs text-slate-600 file:mr-2 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-300 rounded-lg cursor-pointer">
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('customer_profile_modal').classList.add('hidden')" class="bg-slate-100 text-slate-600 font-bold px-4 py-2 rounded-lg text-xs hover:bg-slate-200">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-navy-900 text-white font-bold px-5 py-2 rounded-lg text-xs transition">
                    Save Profile Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Bookings</span>
            <span class="text-3xl font-extrabold text-navy-900">{{ $totalBookings }}</span>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <span class="text-xs font-bold text-amber-600 uppercase tracking-wider block mb-1">Pending Approval</span>
            <span class="text-3xl font-extrabold text-amber-600">{{ $pendingBookings }}</span>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider block mb-1">Confirmed Meetings</span>
            <span class="text-3xl font-extrabold text-emerald-600">{{ $confirmedBookings }}</span>
        </div>
    </div>

    <!-- Bookings Table / List -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-150 flex items-center justify-between">
            <h2 class="font-extrabold text-base text-navy-900">Your Appointment Consultations</h2>
            <span class="text-xs text-slate-500">Sorted by Date</span>
        </div>

        @if($appointments->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                    <i class="fa-solid fa-calendar-xmark"></i>
                </div>
                <h3 class="font-bold text-navy-900 text-base">No Appointments Found</h3>
                <p class="text-slate-500 text-xs mt-1">You haven't scheduled any lawyer consultations yet.</p>
                <a href="{{ route('lawyers.index') }}" class="inline-block mt-4 text-xs font-bold text-blue-600 hover:underline">
                    Browse Lawyers & Book Slot
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5">Appt Code</th>
                            <th class="px-5 py-3.5">Lawyer</th>
                            <th class="px-5 py-3.5">Service</th>
                            <th class="px-5 py-3.5">Date & Slot</th>
                            <th class="px-5 py-3.5">Appt Status</th>
                            <th class="px-5 py-3.5">Payment Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150 font-medium">
                        @foreach($appointments as $apt)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Appointment Code -->
                            <td class="px-5 py-4 font-mono font-bold text-blue-600">
                                {{ $apt->appointment_number }}
                                <div class="text-[10px] text-slate-400 font-sans font-normal">{{ $apt->meeting_place }}</div>
                            </td>

                            <!-- Lawyer Info -->
                            <td class="px-5 py-4">
                                <div class="font-bold text-navy-900">{{ $apt->lawyer->name ?? 'Lawyer' }}</div>
                                <div class="text-[11px] text-slate-500"><i class="fa-solid fa-location-dot text-slate-400"></i> {{ $apt->lawyer->lawyerProfile->location ?? '' }}</div>
                            </td>

                            <!-- Service -->
                            <td class="px-5 py-4">
                                <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md border border-slate-200 text-[11px] font-semibold">
                                    {{ $apt->service->name ?? 'General Counsel' }}
                                </span>
                            </td>

                            <!-- Date & Time Slot -->
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">{{ date('M j, Y', strtotime($apt->appointment_date)) }}</div>
                                <div class="text-[11px] text-slate-500"><i class="fa-solid fa-clock mr-1"></i> {{ date('g:i A', strtotime($apt->start_time)) }} - {{ date('g:i A', strtotime($apt->end_time)) }}</div>
                            </td>

                            <!-- Appointment Status -->
                            <td class="px-5 py-4">
                                @if($apt->status === 'confirmed')
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-emerald-300 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check text-[8px]"></i> Confirmed
                                    </span>
                                @elseif($apt->status === 'pending')
                                    <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-amber-300 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-clock text-[8px]"></i> Pending
                                    </span>
                                @elseif($apt->status === 'completed')
                                    <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-blue-300 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-check-double text-[8px]"></i> Completed
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-red-300 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-ban text-[8px]"></i> Cancelled
                                    </span>
                                @endif
                            </td>

                            <!-- Payment Status -->
                            <td class="px-5 py-4">
                                @if($apt->payment)
                                    @if($apt->payment->isVerified())
                                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-emerald-300 inline-flex items-center gap-1">
                                            <i class="fa-solid fa-shield-check text-[8px]"></i> Verified
                                        </span>
                                        <div class="text-[10px] font-mono text-emerald-700 mt-0.5">{{ $apt->payment->receipt_number }}</div>
                                    @elseif($apt->payment->isPending())
                                        <span class="bg-amber-100 text-amber-900 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-amber-300 inline-flex items-center gap-1">
                                            <i class="fa-solid fa-hourglass-half text-[8px]"></i> Pending Verification
                                        </span>
                                        <div class="text-[10px] text-slate-400 mt-0.5">{{ $apt->payment->payment_method_name }}</div>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-red-300 inline-flex items-center gap-1">
                                            <i class="fa-solid fa-circle-exclamation text-[8px]"></i> Rejected
                                        </span>
                                    @endif
                                @else
                                    @if($apt->status === 'cancelled')
                                        <span class="text-slate-400 text-[11px]">--</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-slate-300 inline-flex items-center gap-1">
                                            <i class="fa-solid fa-circle text-[6px] text-slate-400"></i> Not Paid
                                        </span>
                                    @endif
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    
                                    <!-- Payment Actions -->
                                    @if(!$apt->payment && $apt->status !== 'cancelled')
                                        <a href="{{ route('customer.payment.create', $apt->id) }}" class="bg-blue-600 hover:bg-navy-900 text-white font-bold text-[11px] px-2.5 py-1 rounded transition flex items-center gap-1 shadow-sm">
                                            <i class="fa-solid fa-credit-card"></i> Make Payment
                                        </a>
                                    @elseif($apt->payment)
                                        @if($apt->payment->isPending())
                                            <a href="{{ route('customer.payment.show', $apt->payment->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-[11px] px-2.5 py-1 rounded border border-slate-300 transition flex items-center gap-1">
                                                <i class="fa-solid fa-eye"></i> View Payment
                                            </a>
                                        @elseif($apt->payment->isVerified())
                                            <a href="{{ route('customer.payment.receipt', $apt->payment->id) }}" class="bg-navy-900 hover:bg-blue-600 text-white font-bold text-[11px] px-2.5 py-1 rounded transition flex items-center gap-1">
                                                <i class="fa-solid fa-receipt"></i> Receipt
                                            </a>
                                            <a href="{{ route('customer.payment.receipt.download', $apt->payment->id) }}" title="Download PDF Receipt" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] px-2 py-1 rounded transition flex items-center">
                                                <i class="fa-solid fa-download"></i>
                                            </a>
                                        @elseif($apt->payment->isRejected())
                                            <a href="{{ route('customer.payment.show', $apt->payment->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-[11px] px-2 py-1 rounded border border-slate-300 transition">
                                                View Notes
                                            </a>
                                            <a href="{{ route('customer.payment.create', $apt->id) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold text-[11px] px-2.5 py-1 rounded transition flex items-center gap-1">
                                                <i class="fa-solid fa-rotate-right"></i> Pay Again
                                            </a>
                                        @endif
                                    @endif

                                    <!-- Cancellation Action -->
                                    @if($apt->status === 'pending' || $apt->status === 'confirmed')
                                        <form action="{{ route('customer.appointments.cancel', $apt->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment slot?');" class="inline">
                                            @csrf
                                            <button type="submit" class="text-[11px] text-red-600 font-bold hover:underline px-1 py-1">
                                                Cancel Slot
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
</div>
@endsection
