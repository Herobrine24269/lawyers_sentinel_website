<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\LawyerProfile;
use App\Models\Service;
use App\Models\LawyerSchedule;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->status === 'suspended') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been suspended by administration.']);
            }

            return $this->redirectUser($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showCustomerRegister()
    {
        return view('auth.register-customer');
    }

    public function registerCustomer(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:50'],
            'profile_pic' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $profilePicPath = null;
        if ($request->hasFile('profile_pic')) {
            $profilePicPath = $request->file('profile_pic')->store('profile_pics', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'profile_pic' => $profilePicPath,
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'status' => 'active',
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please log in with your account credentials.');
    }

    public function showLawyerRegister()
    {
        $services = Service::where('is_active', true)->get();
        return view('auth.register-lawyer', compact('services'));
    }

    public function registerLawyer(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:50'],
            'profile_pic' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'location' => ['required', 'string', 'max:255'],
            'meeting_place' => ['required', 'string', 'max:255'],
            'specializations' => ['required', 'array'],
            'experience_years' => ['required', 'numeric', 'min:0'],
            'license_number' => ['required', 'string', 'max:100'],
            'hourly_fee' => ['required', 'numeric', 'min:0'],
            'bio' => ['nullable', 'string'],
        ]);

        $profilePicPath = null;
        if ($request->hasFile('profile_pic')) {
            $profilePicPath = $request->file('profile_pic')->store('profile_pics', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'profile_pic' => $profilePicPath,
            'password' => Hash::make($validated['password']),
            'role' => 'lawyer',
            'status' => 'active',
        ]);

        LawyerProfile::create([
            'user_id' => $user->id,
            'location' => $validated['location'],
            'meeting_place' => $validated['meeting_place'],
            'specializations' => array_map('intval', $validated['specializations']),
            'experience_years' => $validated['experience_years'],
            'license_number' => $validated['license_number'],
            'hourly_fee' => $validated['hourly_fee'],
            'bio' => $validated['bio'] ?? 'Certified attorney providing legal counsel.',
            'rating' => 5.0,
            'total_reviews' => 0,
            'is_featured' => false,
            'is_approved' => true,
        ]);

        // Default week schedule
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        foreach ($days as $day) {
            LawyerSchedule::create([
                'lawyer_id' => $user->id,
                'day_of_week' => $day,
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'slot_duration_minutes' => 45,
                'is_available' => true,
            ]);
        }

        return redirect()->route('login')->with('success', 'Lawyer registration completed successfully! Please log in with your credentials.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'You have been logged out.');
    }

    private function redirectUser(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isLawyer()) {
            return redirect()->route('lawyer.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    }

    // Forgot Password & Reset Password

    public function showForgotPassword()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request, $token = null)
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email ?? old('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('success', __($status));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
