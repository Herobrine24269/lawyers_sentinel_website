<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lawyer Panel - Juris Sentinel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }</style>
</head>
<body class="flex flex-col min-h-screen">

    <!-- Lawyer Top Navbar -->
    <header class="bg-slate-900 text-white h-16 flex items-center justify-between px-6 sticky top-0 z-50 border-b border-slate-800">
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <i class="fa-solid fa-gavel text-amber-400 text-lg"></i>
                <span class="font-extrabold text-base tracking-tight">LAWYER PORTAL</span>
            </a>
            <span class="bg-amber-900/60 text-amber-300 text-[10px] uppercase font-bold px-2 py-0.5 rounded border border-amber-700">Attorney Console</span>
        </div>
        <div class="flex items-center gap-4 text-xs font-semibold">
            @auth
                <div class="flex items-center gap-2">
                    <img src="{{ auth()->user()->profile_pic_url }}" alt="{{ auth()->user()->name }}"
                        class="w-8 h-8 rounded-full object-cover border-2 border-amber-400 shadow-sm">
                    <span class="text-slate-200 hidden sm:inline">{{ auth()->user()->name }}</span>
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
        <!-- Lawyer Sidebar -->
        <aside class="w-64 bg-slate-900 text-slate-300 p-4 space-y-2 border-r border-slate-800 hidden md:block">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider px-3 mb-2">Lawyer Workspace</div>

            <a href="{{ route('lawyer.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('lawyer.dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie text-blue-400 w-5"></i> Overview Dashboard
            </a>

            <a href="{{ route('lawyer.profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('lawyer.profile*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-id-card text-amber-400 w-5"></i> My Profile & Details
            </a>

            <a href="{{ route('lawyer.schedule') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('lawyer.schedule*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-clock text-emerald-400 w-5"></i> Schedule & Slot Settings
            </a>

            <a href="{{ route('lawyer.appointments') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('lawyer.appointments*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-calendar-check text-purple-400 w-5"></i> Appointment Requests
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
