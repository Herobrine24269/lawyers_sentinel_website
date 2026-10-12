@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-16 px-4">
    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-8">
        
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-xl bg-navy-900 text-amber-400 flex items-center justify-center mx-auto mb-3 text-xl shadow-sm">
                <i class="fa-solid fa-key"></i>
            </div>
            <h1 class="text-2xl font-bold text-navy-900 tracking-tight">Reset Your Password</h1>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                Please enter your email and choose a strong new password to secure your account.
            </p>
        </div>

        @if(isset($errors) && $errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-xs rounded-lg p-3.5 mb-6 flex items-start gap-2">
            <i class="fa-solid fa-circle-exclamation text-red-600 text-sm mt-0.5"></i>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Hidden Token -->
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus
                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition"
                        placeholder="e.g. attorney@juris.com">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">New Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" required
                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition"
                        placeholder="••••••••">
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Minimum 6 characters</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Confirm New Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password_confirmation" required
                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition"
                        placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="w-full bg-navy-900 hover:bg-blue-600 text-white font-bold py-3 rounded-lg text-sm transition shadow-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-check text-xs text-amber-400"></i> Reset Password
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
            <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline inline-flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-xs"></i> Back to Login
            </a>
        </div>

    </div>
</div>
@endsection
