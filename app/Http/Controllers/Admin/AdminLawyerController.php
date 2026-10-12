<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LawyerProfile;

class AdminLawyerController extends Controller
{
    public function index()
    {
        $lawyers = User::where('role', 'lawyer')
            ->with('lawyerProfile')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.lawyers.index', compact('lawyers'));
    }

    public function toggleApproval(Request $request, $id)
    {
        $lawyer = User::where('role', 'lawyer')->where('id', $id)->firstOrFail();
        $profile = $lawyer->lawyerProfile;

        if ($profile) {
            $profile->is_approved = !$profile->is_approved;
            $profile->save();
        }

        return back()->with('success', 'Lawyer profile approval status updated successfully!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $lawyer = User::where('role', 'lawyer')->where('id', $id)->firstOrFail();
        $lawyer->status = ($lawyer->status === 'active') ? 'suspended' : 'active';
        $lawyer->save();

        return back()->with('success', 'Lawyer account status updated to ' . ucfirst($lawyer->status));
    }

    public function toggleFeatured(Request $request, $id)
    {
        $lawyer = User::where('role', 'lawyer')->where('id', $id)->firstOrFail();
        $profile = $lawyer->lawyerProfile;

        if ($profile) {
            $profile->is_featured = !$profile->is_featured;
            $profile->save();
        }

        return back()->with('success', 'Lawyer feature status toggled!');
    }

    public function updateProfilePic(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'profile_pic' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);

        if ($user->profile_pic && file_exists(storage_path('app/public/' . $user->profile_pic))) {
            @unlink(storage_path('app/public/' . $user->profile_pic));
        }

        $path = $request->file('profile_pic')->store('profile_pics', 'public');
        $user->update(['profile_pic' => $path]);

        return back()->with('success', "Profile picture updated successfully for {$user->name}!");
    }

    public function updateAdminProfile(Request $request)
    {
        $admin = \Illuminate\Support\Facades\Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'profile_pic' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('profile_pic')) {
            if ($admin->profile_pic && file_exists(storage_path('app/public/' . $admin->profile_pic))) {
                @unlink(storage_path('app/public/' . $admin->profile_pic));
            }
            $data['profile_pic'] = $request->file('profile_pic')->store('profile_pics', 'public');
        }

        $admin->update($data);

        return back()->with('success', 'Admin profile and picture updated!');
    }
}
