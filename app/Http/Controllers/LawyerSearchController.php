<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LawyerProfile;
use App\Models\Service;
use App\Models\LawyerSchedule;
use App\Models\Appointment;
use Carbon\Carbon;

class LawyerSearchController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::where('is_active', true)->get();

        $serviceFilter = $request->query('service');
        $locationFilter = $request->query('location');
        $meetingPlaceFilter = $request->query('meeting_place');
        $keyword = $request->query('q');

        $query = User::where('role', 'lawyer')
            ->where('status', 'active')
            ->whereHas('lawyerProfile', function ($q) {
                $q->where('is_approved', true);
            })
            ->with(['lawyerProfile', 'lawyerSchedules']);

        // Filter by keyword (Name)
        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        // Filter by location
        if ($locationFilter) {
            $query->whereHas('lawyerProfile', function ($q) use ($locationFilter) {
                $q->where('location', 'like', "%{$locationFilter}%");
            });
        }

        // Filter by meeting place
        if ($meetingPlaceFilter) {
            $query->whereHas('lawyerProfile', function ($q) use ($meetingPlaceFilter) {
                $q->where('meeting_place', 'like', "%{$meetingPlaceFilter}%");
            });
        }

        $lawyers = $query->get();

        // Filter by service in PHP if service filter present
        if ($serviceFilter) {
            $selectedService = Service::where('slug', $serviceFilter)->orWhere('id', $serviceFilter)->first();
            if ($selectedService) {
                $lawyers = $lawyers->filter(function ($lawyer) use ($selectedService) {
                    $specs = $lawyer->lawyerProfile->specializations ?? [];
                    return in_array($selectedService->id, $specs) || in_array((string)$selectedService->id, $specs);
                });
            }
        }

        $selectedServiceObj = null;
        if ($serviceFilter) {
            $selectedServiceObj = Service::where('slug', $serviceFilter)->orWhere('id', $serviceFilter)->first();
        }

        return view('lawyers.index', compact(
            'lawyers',
            'services',
            'serviceFilter',
            'locationFilter',
            'meetingPlaceFilter',
            'keyword',
            'selectedServiceObj'
        ));
    }

    public function show(Request $request, $id)
    {
        $lawyer = User::where('role', 'lawyer')
            ->where('id', $id)
            ->with(['lawyerProfile', 'lawyerSchedules'])
            ->firstOrFail();

        $services = Service::whereIn('id', $lawyer->lawyerProfile->specializations ?? [])->get();
        if ($services->isEmpty()) {
            $services = Service::where('is_active', true)->take(3)->get();
        }

        // Selected Date for Slot Generation
        $selectedDate = $request->query('date', date('Y-m-d'));
        $dayOfWeek = Carbon::parse($selectedDate)->format('l'); // Monday, Tuesday, etc.

        // Get lawyer's schedule for that day
        $schedule = LawyerSchedule::where('lawyer_id', $lawyer->id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        $availableSlots = [];
        if ($schedule && $schedule->is_available) {
            $startTime = Carbon::parse($selectedDate . ' ' . $schedule->start_time);
            $endTime = Carbon::parse($selectedDate . ' ' . $schedule->end_time);
            $duration = $schedule->slot_duration_minutes ?? 30;

            // Get booked slots for this lawyer on this date
            $bookedAppointments = Appointment::where('lawyer_id', $lawyer->id)
                ->where('appointment_date', $selectedDate)
                ->whereIn('status', ['pending', 'confirmed'])
                ->get();

            while ($startTime->copy()->addMinutes($duration)->lte($endTime)) {
                $slotStartStr = $startTime->format('H:i:s');
                $slotEndStr = $startTime->copy()->addMinutes($duration)->format('H:i:s');

                // Check if already booked
                $isBooked = $bookedAppointments->contains(function ($apt) use ($slotStartStr) {
                    return $apt->start_time === $slotStartStr || date('H:i:s', strtotime($apt->start_time)) === $slotStartStr;
                });

                $availableSlots[] = [
                    'start' => $slotStartStr,
                    'end' => $slotEndStr,
                    'display' => $startTime->format('g:i A') . ' - ' . $startTime->copy()->addMinutes($duration)->format('g:i A'),
                    'is_booked' => $isBooked,
                ];

                $startTime->addMinutes($duration);
            }
        }

        return view('lawyers.show', compact('lawyer', 'services', 'selectedDate', 'schedule', 'availableSlots'));
    }

    public function searchApi(Request $request)
    {
        $services = Service::where('is_active', true)->get();

        $serviceFilter = $request->query('service');
        $locationFilter = $request->query('location');
        $meetingPlaceFilter = $request->query('meeting_place');
        $keyword = $request->query('q');

        $query = User::where('role', 'lawyer')
            ->where('status', 'active')
            ->whereHas('lawyerProfile', function ($q) {
                $q->where('is_approved', true);
            })
            ->with(['lawyerProfile']);

        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($locationFilter) {
            $query->whereHas('lawyerProfile', function ($q) use ($locationFilter) {
                $q->where('location', 'like', "%{$locationFilter}%");
            });
        }

        if ($meetingPlaceFilter) {
            $query->whereHas('lawyerProfile', function ($q) use ($meetingPlaceFilter) {
                $q->where('meeting_place', 'like', "%{$meetingPlaceFilter}%");
            });
        }

        $lawyers = $query->get();

        if ($serviceFilter) {
            $selectedService = Service::where('slug', $serviceFilter)->orWhere('id', $serviceFilter)->first();
            if ($selectedService) {
                $lawyers = $lawyers->filter(function ($lawyer) use ($selectedService) {
                    $specs = $lawyer->lawyerProfile->specializations ?? [];
                    return in_array($selectedService->id, $specs) || in_array((string)$selectedService->id, $specs);
                })->values();
            }
        }

        $formattedLawyers = $lawyers->map(function ($lawyer) use ($services) {
            $profile = $lawyer->lawyerProfile;
            $specNames = [];
            if ($profile && $profile->specializations) {
                foreach ($services as $svc) {
                    if (in_array($svc->id, $profile->specializations ?? [])) {
                        $specNames[] = $svc->name;
                    }
                }
            }

            return [
                'id' => $lawyer->id,
                'name' => $lawyer->name,
                'initials' => strtoupper(substr($lawyer->name, 0, 2)),
                'profile_pic_url' => $lawyer->profile_pic_url,
                'location' => $profile->location ?? 'N/A',
                'experience_years' => $profile->experience_years ?? 5,
                'license_number' => $profile->license_number ?? 'N/A',
                'hourly_fee' => number_format($profile->hourly_fee ?? 150, 2),
                'meeting_place' => $profile->meeting_place ?? 'Office',
                'rating' => $profile->rating ?? '5.0',
                'bio' => $profile->bio ?? '',
                'specializations' => $specNames,
                'profile_url' => route('lawyers.show', $lawyer->id),
            ];
        });

        return response()->json([
            'success' => true,
            'count' => count($formattedLawyers),
            'lawyers' => $formattedLawyers
        ]);
    }

    public function getSlotsApi(Request $request, $id)
    {
        $lawyer = User::where('role', 'lawyer')
            ->where('id', $id)
            ->with(['lawyerProfile', 'lawyerSchedules'])
            ->firstOrFail();

        $selectedDate = $request->query('date', date('Y-m-d'));
        $dayOfWeek = Carbon::parse($selectedDate)->format('l');

        $schedule = LawyerSchedule::where('lawyer_id', $lawyer->id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        $availableSlots = [];
        if ($schedule && $schedule->is_available) {
            $startTime = Carbon::parse($selectedDate . ' ' . $schedule->start_time);
            $endTime = Carbon::parse($selectedDate . ' ' . $schedule->end_time);
            $duration = $schedule->slot_duration_minutes ?? 30;

            $bookedAppointments = Appointment::where('lawyer_id', $lawyer->id)
                ->where('appointment_date', $selectedDate)
                ->whereIn('status', ['pending', 'confirmed'])
                ->get();

            while ($startTime->copy()->addMinutes($duration)->lte($endTime)) {
                $slotStartStr = $startTime->format('H:i:s');
                $slotEndStr = $startTime->copy()->addMinutes($duration)->format('H:i:s');

                $isBooked = $bookedAppointments->contains(function ($apt) use ($slotStartStr) {
                    return $apt->start_time === $slotStartStr || date('H:i:s', strtotime($apt->start_time)) === $slotStartStr;
                });

                $availableSlots[] = [
                    'start' => $slotStartStr,
                    'end' => $slotEndStr,
                    'display' => $startTime->format('g:i A') . ' - ' . $startTime->copy()->addMinutes($duration)->format('g:i A'),
                    'is_booked' => $isBooked,
                ];

                $startTime->addMinutes($duration);
            }
        }

        return response()->json([
            'success' => true,
            'date' => $selectedDate,
            'date_formatted' => date('D, M j, Y', strtotime($selectedDate)),
            'slot_duration' => $schedule->slot_duration_minutes ?? 30,
            'is_working_day' => ($schedule && $schedule->is_available),
            'slots' => $availableSlots
        ]);
    }
}
