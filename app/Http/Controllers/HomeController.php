<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\LawyerProfile;
use App\Models\HomepageContent;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $content = HomepageContent::first() ?? new HomepageContent();
        $services = Service::where('is_active', true)->get();

        // Featured Lawyers with minimal profile info
        $featuredLawyers = User::where('role', 'lawyer')
            ->where('status', 'active')
            ->whereHas('lawyerProfile', function ($query) {
                $query->where('is_approved', true);
            })
            ->with('lawyerProfile')
            ->take(4)
            ->get();

        // Dynamic stats
        $totalLawyers = User::where('role', 'lawyer')->where('status', 'active')->count();
        $totalAppointments = \App\Models\Appointment::count();

        return view('home', compact('content', 'services', 'featuredLawyers', 'totalLawyers', 'totalAppointments'));
    }
}
