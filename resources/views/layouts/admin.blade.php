<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Juris Sentinel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }</style>
</head>
<body class="flex flex-col min-h-screen">

    <!-- Admin Top Navbar -->
    <header class="bg-slate-900 text-white h-16 flex items-center justify-between px-6 sticky top-0 z-50 border-b border-slate-800">
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <i class="fa-solid fa-gavel text-amber-400 text-lg"></i>
                <span class="font-extrabold text-base tracking-tight">JURIS ADMIN</span>
            </a>
            <span class="bg-purple-900/60 text-purple-300 text-[10px] uppercase font-bold px-2 py-0.5 rounded border border-purple-700">System Management</span>
        </div>
        <div class="flex items-center gap-4 text-xs font-semibold">
            @auth
                <div class="flex items-center gap-2 relative">
                    <img src="{{ auth()->user()->profile_pic_url }}" alt="{{ auth()->user()->name }}"
                        class="w-8 h-8 rounded-full object-cover border-2 border-purple-500 shadow-sm cursor-pointer"
                        onclick="document.getElementById('admin_pic_modal').classList.toggle('hidden')"
                        title="Click to edit profile picture">
                    <span class="text-slate-200 hidden sm:inline">{{ auth()->user()->name }}</span>

                    <!-- Admin Profile Pic Edit Modal -->
                    <div id="admin_pic_modal" class="hidden absolute right-0 top-10 w-64 bg-slate-800 border border-slate-700 p-3 rounded-lg shadow-xl text-xs z-50">
                        <span class="font-bold text-slate-200 block mb-2">Update Admin Profile Picture</span>
                        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                            @csrf
                            <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                            <input type="file" name="profile_pic" accept="image/*" required
                                class="w-full text-[10px] text-slate-300 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-purple-600 file:text-white border border-slate-600 rounded bg-slate-900">
                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-1.5 rounded text-[11px] transition">
                                Upload Admin Picture
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            <a href="{{ route('home') }}" class="text-slate-400 hover:text-white transition">
                <i class="fa-solid fa-globe mr-1"></i> Public Site
            </a>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-900/40 text-red-300 hover:bg-red-800 hover:text-white px-3 py-1.5 rounded transition">
                    <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
                </button>
            </form>
        </div>
    </header>

    <div class="flex flex-grow">
        <!-- Admin Sidebar -->
        <aside class="w-64 bg-slate-900 text-slate-300 p-4 space-y-2 border-r border-slate-800 hidden md:block">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider px-3 mb-2">Navigation</div>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-chart-line text-blue-400 w-5"></i> Dashboard Overview
            </a>

            <a href="{{ route('admin.lawyers') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.lawyers*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-user-shield text-amber-400 w-5"></i> Manage Lawyers & Profiles
            </a>

            <a href="{{ route('admin.services') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.services*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-gavel text-emerald-400 w-5"></i> Manage Legal Services
            </a>

            <a href="{{ route('admin.appointments') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.appointments*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-calendar-days text-purple-400 w-5"></i> Manage Appointments & Slots
            </a>

            <a href="{{ route('admin.payments') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.payments*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-receipt text-emerald-400 w-5"></i> Payments & Receipts
            </a>

            <a href="{{ route('admin.payment-settings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.payment-settings*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-credit-card text-teal-400 w-5"></i> Payment Gateway Settings
            </a>

            <a href="{{ route('admin.homepage') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.homepage*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-sliders text-sky-400 w-5"></i> Manage Homepage Content
            </a>
        </aside>

        <!-- Main Content -->
        <main class="flex-grow p-6 md:p-8">
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm py-3 px-4 rounded-lg mb-6 flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
