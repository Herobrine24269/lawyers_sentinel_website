@extends('layouts.lawyer')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Manage Customer Appointment Requests</h1>
            <p class="text-xs text-slate-500 mt-1">Review incoming booking requests, accept/confirm slots, or update consultation statuses</p>
        </div>
        <span class="text-xs bg-blue-100 text-blue-800 font-bold px-3 py-1.5 rounded-lg border border-blue-200">
            Total: {{ count($appointments) }}
        </span>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        @if($appointments->isEmpty())
            <div class="p-10 text-center text-xs text-slate-500">
                No customer appointment requests found.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Appt Number</th>
                            <th class="px-6 py-3.5">Customer Details</th>
                            <th class="px-6 py-3.5">Matter / Service</th>
                            <th class="px-6 py-3.5">Date & Time Slot</th>
                            <th class="px-6 py-3.5">Customer Notes</th>
                            <th class="px-6 py-3.5">Current Status</th>
                            <th class="px-6 py-3.5 text-right">Update Status Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150 font-medium">
                        @foreach($appointments as $apt)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-mono font-bold text-blue-600">{{ $apt->appointment_number }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $apt->customer->name ?? 'Customer' }}</div>
                                <div class="text-[11px] text-slate-500"><i class="fa-solid fa-envelope text-slate-400"></i> {{ $apt->customer->email ?? '' }}</div>
                                <div class="text-[11px] text-slate-500"><i class="fa-solid fa-phone text-slate-400"></i> {{ $apt->customer->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded border border-slate-200 text-[11px] font-semibold">
                                    {{ $apt->service->name ?? 'General Counsel' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ date('M j, Y', strtotime($apt->appointment_date)) }}</div>
                                <div class="text-[11px] text-slate-500"><i class="fa-solid fa-clock mr-1"></i> {{ date('g:i A', strtotime($apt->start_time)) }} - {{ date('g:i A', strtotime($apt->end_time)) }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">Format: {{ $apt->meeting_place }}</div>
                            </td>
                            <td class="px-6 py-4 max-w-xs text-xs text-slate-600 truncate" title="{{ $apt->notes }}">
                                {{ $apt->notes ?: 'No notes provided' }}
                            </td>
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
                                <form action="{{ route('lawyer.appointments.status', $apt->id) }}" method="POST" class="inline-flex gap-1">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded px-2 py-1 text-xs font-semibold text-slate-800">
                                        <option value="pending" {{ $apt->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $apt->status === 'confirmed' ? 'selected' : '' }}>Confirm Slot</option>
                                        <option value="completed" {{ $apt->status === 'completed' ? 'selected' : '' }}>Mark Completed</option>
                                        <option value="cancelled" {{ $apt->status === 'cancelled' ? 'selected' : '' }}>Cancel Request</option>
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
