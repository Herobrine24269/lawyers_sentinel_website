<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = User::where('role', 'customer')->count();
        $totalLawyers = User::where('role', 'lawyer')->count();
        $totalServices = Service::count();
        $totalAppointments = Appointment::count();

        $recentAppointments = Appointment::with(['customer', 'lawyer', 'service'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $pendingLawyers = User::where('role', 'lawyer')
            ->whereHas('lawyerProfile', function ($q) {
                $q->where('is_approved', false);
            })
            ->with('lawyerProfile')
            ->get();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalLawyers',
            'totalServices',
            'totalAppointments',
            'recentAppointments',
            'pendingLawyers'
        ));
    }
}
