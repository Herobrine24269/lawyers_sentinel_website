<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function book(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must log in to book an appointment.');
        }

        if (!Auth::user()->isCustomer()) {
            return back()->with('error', 'Only customer accounts can book lawyer appointments.');
        }

        $validated = $request->validate([
            'lawyer_id' => ['required', 'exists:users,id'],
            'service_id' => ['required', 'exists:services,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'string'],
            'end_time' => ['required', 'string'],
            'meeting_place' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Double booking check
        $exists = Appointment::where('lawyer_id', $validated['lawyer_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->where('start_time', $validated['start_time'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($exists) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected time slot has already been booked. Please choose another time slot.'
                ], 422);
            }
            return back()->with('error', 'The selected time slot has already been booked. Please choose another time slot.');
        }

        $appointmentNumber = 'APT-' . date('Y') . '-' . strtoupper(substr(uniqid(), -5));

        $appointment = Appointment::create([
            'appointment_number' => $appointmentNumber,
            'customer_id' => Auth::id(),
            'lawyer_id' => $validated['lawyer_id'],
            'service_id' => $validated['service_id'],
            'appointment_date' => $validated['appointment_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'meeting_place' => $validated['meeting_place'],
            'notes' => $validated['notes'] ?? '',
            'status' => 'pending',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment successfully booked! Appointment Number: ' . $appointmentNumber,
                'appointment_number' => $appointmentNumber,
                'redirect_url' => route('customer.dashboard')
            ]);
        }

        return redirect()->route('customer.dashboard')->with('success', 'Appointment successfully booked! Appointment Number: ' . $appointmentNumber);
    }
}
