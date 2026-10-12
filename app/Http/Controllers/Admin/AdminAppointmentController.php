<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\LawyerSchedule;

class AdminAppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['customer', 'lawyer.lawyerProfile', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        $schedules = LawyerSchedule::with('lawyer')->get()->groupBy('lawyer_id');

        return view('admin.appointments.index', compact('appointments', 'schedules'));
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $appointment->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Appointment status updated by Admin.');
    }
}
