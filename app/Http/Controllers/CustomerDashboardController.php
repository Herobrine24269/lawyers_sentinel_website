<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $appointments = Appointment::where('customer_id', $user->id)
            ->with(['lawyer.lawyerProfile', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        $totalBookings = $appointments->count();
        $pendingBookings = $appointments->where('status', 'pending')->count();
        $confirmedBookings = $appointments->where('status', 'confirmed')->count();

        return view('customer.dashboard', compact('user', 'appointments', 'totalBookings', 'pendingBookings', 'confirmedBookings'));
    }

    public function cancelAppointment(Request $request, $id)
    {
        $appointment = Appointment::where('customer_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'cancellation_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason ?? 'Cancelled by customer',
        ]);

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'profile_pic' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);

        $userData = [
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ];

        if ($request->hasFile('profile_pic')) {
            if ($user->profile_pic && file_exists(storage_path('app/public/' . $user->profile_pic))) {
                @unlink(storage_path('app/public/' . $user->profile_pic));
            }
            $userData['profile_pic'] = $request->file('profile_pic')->store('profile_pics', 'public');
        }

        $user->update($userData);

        return back()->with('success', 'Profile and picture updated successfully!');
    }
}
