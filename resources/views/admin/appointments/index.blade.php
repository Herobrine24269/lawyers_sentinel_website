@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Manage System Appointments & Booking Slots</h1>
            <p class="text-xs text-slate-500 mt-1">Global audit and override control over lawyer consultation slots and customer bookings</p>
        </div>
        <span class="text-xs bg-purple-100 text-purple-800 font-bold px-3 py-1.5 rounded-lg border border-purple-200">
            Total Appointments: {{ count($appointments) }}
        </span>
    </div>

    <!-- All Appointments System Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-150 font-bold text-sm text-slate-900 flex justify-between items-center">
            <span>System-Wide Booked Consultation Slots</span>
            <span class="text-xs text-slate-500 font-normal">Real-time status updates</span>
        </div>

        @if($appointments->isEmpty())
            <div class="p-10 text-center text-xs text-slate-500">
                No appointments booked in the system yet.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Appt Code</th>
                            <th class="px-6 py-3.5">Customer Name</th>
                            <th class="px-6 py-3.5">Assigned Lawyer</th>
                            <th class="px-6 py-3.5">Legal Service</th>
                            <th class="px-6 py-3.5">Date & Time Slot</th>
                            <th class="px-6 py-3.5">Meeting Format</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Admin Override</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150 font-medium">
                        @foreach($appointments as $apt)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-mono font-bold text-blue-600">{{ $apt->appointment_number }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $apt->customer->name ?? 'Customer' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $apt->customer->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $apt->lawyer->name ?? 'Lawyer' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $apt->lawyer->lawyerProfile->location ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $apt->service->name ?? 'General' }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ date('M j, Y', strtotime($apt->appointment_date)) }}</div>
                                <div class="text-[11px] text-slate-500">{{ date('g:i A', strtotime($apt->start_time)) }} - {{ date('g:i A', strtotime($apt->end_time)) }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $apt->meeting_place }}</td>
                            <td class="px-6 py-4">
                                @if($apt->status === 'confirmed')
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-300">Confirmed</span>
                                @elseif($apt->status === 'pending')
                                    <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded border border-amber-300">Pending</span>
                                @elseif($apt->status === 'completed')
                                    <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-300">Completed</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2 py-0.5 rounded border border-red-300">Cancelled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.appointments.status', $apt->id) }}" method="POST" class="inline">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded px-2 py-1 text-xs font-semibold text-slate-800">
                                        <option value="pending" {{ $apt->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $apt->status === 'confirmed' ? 'selected' : '' }}>Confirm</option>
                                        <option value="completed" {{ $apt->status === 'completed' ? 'selected' : '' }}>Complete</option>
                                        <option value="cancelled" {{ $apt->status === 'cancelled' ? 'selected' : '' }}>Cancel</option>
                                    </select>
                                </form>
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
