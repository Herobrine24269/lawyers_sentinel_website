<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\LawyerProfile;
use App\Models\LawyerSchedule;
use App\Models\Service;

class LawyerDashboardController extends Controller
{
    public function index()
    {
        $lawyer = Auth::user();

        $appointments = Appointment::where('lawyer_id', $lawyer->id)
            ->with(['customer', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        $pendingCount = $appointments->where('status', 'pending')->count();
        $confirmedCount = $appointments->where('status', 'confirmed')->count();
        $completedCount = $appointments->where('status', 'completed')->count();

        $profile = $lawyer->lawyerProfile;

        return view('lawyer.dashboard', compact('lawyer', 'appointments', 'pendingCount', 'confirmedCount', 'completedCount', 'profile'));
    }

    public function editProfile()
    {
        $lawyer = Auth::user();
        $profile = $lawyer->lawyerProfile;
        $services = Service::where('is_active', true)->get();

        return view('lawyer.profile', compact('lawyer', 'profile', 'services'));
    }

    public function updateProfile(Request $request)
    {
        $lawyer = Auth::user();
        $profile = $lawyer->lawyerProfile;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'profile_pic' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'location' => ['required', 'string', 'max:255'],
            'meeting_place' => ['required', 'string', 'max:255'],
            'specializations' => ['required', 'array'],
            'experience_years' => ['required', 'numeric', 'min:0'],
            'license_number' => ['required', 'string', 'max:100'],
            'hourly_fee' => ['required', 'numeric', 'min:0'],
            'bio' => ['required', 'string'],
        ]);

        $userData = [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ];

        if ($request->hasFile('profile_pic')) {
            if ($lawyer->profile_pic && file_exists(storage_path('app/public/' . $lawyer->profile_pic))) {
                @unlink(storage_path('app/public/' . $lawyer->profile_pic));
            }
            $userData['profile_pic'] = $request->file('profile_pic')->store('profile_pics', 'public');
        }

        $lawyer->update($userData);

        $profile->update([
            'location' => $validated['location'],
            'meeting_place' => $validated['meeting_place'],
            'specializations' => array_map('intval', $validated['specializations']),
            'experience_years' => $validated['experience_years'],
            'license_number' => $validated['license_number'],
            'hourly_fee' => $validated['hourly_fee'],
            'bio' => $validated['bio'],
        ]);

        return back()->with('success', 'Lawyer profile updated successfully!');
    }

    public function schedule()
    {
        $lawyer = Auth::user();
        $schedules = LawyerSchedule::where('lawyer_id', $lawyer->id)->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('lawyer.schedule', compact('lawyer', 'schedules', 'days'));
    }

    public function updateSchedule(Request $request)
    {
        $lawyer = Auth::user();
        $schedulesData = $request->input('schedules', []);

        foreach ($schedulesData as $day => $data) {
            LawyerSchedule::updateOrCreate(
                [
                    'lawyer_id' => $lawyer->id,
                    'day_of_week' => $day,
                ],
                [
                    'start_time' => $data['start_time'] ?? '09:00:00',
                    'end_time' => $data['end_time'] ?? '17:00:00',
                    'slot_duration_minutes' => $data['slot_duration_minutes'] ?? 30,
                    'is_available' => isset($data['is_available']),
                ]
            );
        }

        return back()->with('success', 'Availability schedule updated successfully!');
    }

    public function appointments()
    {
        $lawyer = Auth::user();
        $appointments = Appointment::where('lawyer_id', $lawyer->id)
            ->with(['customer', 'service'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('lawyer.appointments', compact('lawyer', 'appointments'));
    }

    public function updateAppointmentStatus(Request $request, $id)
    {
        $appointment = Appointment::where('lawyer_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
            'cancellation_reason' => ['nullable', 'string'],
        ]);

        $appointment->update([
            'status' => $request->status,
            'cancellation_reason' => $request->cancellation_reason ?? $appointment->cancellation_reason,
        ]);

        return back()->with('success', 'Appointment status updated to ' . ucfirst($request->status));
    }
}
