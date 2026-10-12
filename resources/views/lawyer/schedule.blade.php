@extends('layouts.lawyer')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <h1 class="text-xl font-extrabold text-slate-900">Manage Availability Schedules & Slots</h1>
        <p class="text-xs text-slate-500 mt-1">Define working days, consultation hours, and slot durations for customer booking</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <form action="{{ route('lawyer.schedule.update') }}" method="POST">
            @csrf

            <div class="space-y-4">
                @foreach($days as $day)
                @php 
                    $sch = $schedules->firstWhere('day_of_week', $day); 
                    $isAvail = $sch ? $sch->is_available : in_array($day, ['Monday','Tuesday','Wednesday','Thursday','Friday']);
                @endphp
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    
                    <div class="w-36 flex items-center">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-sm text-slate-800">
                            <input type="checkbox" name="schedules[{{ $day }}][is_available]" value="1" {{ $isAvail ? 'checked' : '' }}
                                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span>{{ $day }}</span>
                        </label>
                    </div>

                    <div class="flex items-center gap-3 flex-1 flex-wrap text-xs">
                        <div>
                            <span class="text-[10px] text-slate-400 uppercase font-bold block mb-0.5">Start Time</span>
                            <input type="time" name="schedules[{{ $day }}][start_time]" value="{{ $sch ? substr($sch->start_time,0,5) : '09:00' }}"
                                class="px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs bg-white">
                        </div>

                        <span class="text-slate-400 mt-3">to</span>

                        <div>
                            <span class="text-[10px] text-slate-400 uppercase font-bold block mb-0.5">End Time</span>
                            <input type="time" name="schedules[{{ $day }}][end_time]" value="{{ $sch ? substr($sch->end_time,0,5) : '17:00' }}"
                                class="px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs bg-white">
                        </div>

                        <div>
                            <span class="text-[10px] text-slate-400 uppercase font-bold block mb-0.5">Slot Duration</span>
                            <select name="schedules[{{ $day }}][slot_duration_minutes]" class="px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs bg-white font-semibold">
                                <option value="30" {{ ($sch->slot_duration_minutes ?? 30) == 30 ? 'selected' : '' }}>30 Minutes</option>
                                <option value="45" {{ ($sch->slot_duration_minutes ?? 30) == 45 ? 'selected' : '' }}>45 Minutes</option>
                                <option value="60" {{ ($sch->slot_duration_minutes ?? 30) == 60 ? 'selected' : '' }}>60 Minutes</option>
                            </select>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

            <button type="submit" class="mt-6 bg-blue-600 hover:bg-slate-900 text-white font-bold px-6 py-2.5 rounded-lg text-xs transition">
                Update Schedule Settings
            </button>
        </form>
    </div>

</div>
@endsection
