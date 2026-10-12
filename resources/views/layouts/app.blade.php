<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Juris Sentinel - Lawyer Portal') }}</title>
    <!-- Google Fonts: Inter, Dancing Script, Instrument Serif -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                        instrument: ['Instrument Serif', 'serif'],
                        dancing: ['Dancing Script', 'cursive'],
                    },
                    colors: {
                        navy: {
                            50: '#f0f4f8',
                            100: '#d9e2ec',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#0b0f19',
                        },
                        accent: {
                            blue: '#2563eb',
                            gold: '#d97706',
                            emerald: '#059669',
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid #e2e8f0; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.25s ease-out forwards; }

        .font-inter { font-family: 'Inter', sans-serif; }
        .font-instrument { font-family: 'Instrument Serif', serif; }
        .font-dancing { font-family: 'Dancing Script', cursive; }

        .liquid-glass {
          background: rgba(255, 255, 255, 0.08);
          background-blend-mode: luminosity;
          backdrop-filter: blur(8px);
          -webkit-backdrop-filter: blur(8px);
          border: none;
          box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.2);
          position: relative;
          overflow: hidden;
        }

        .liquid-glass::before {
          content: '';
          position: absolute;
          inset: 0;
          border-radius: inherit;
          padding: 1.4px;
          background: linear-gradient(180deg,
            rgba(255,255,255,0.45) 0%, rgba(255,255,255,0.15) 20%,
            rgba(255,255,255,0) 40%, rgba(255,255,255,0) 60%,
            rgba(255,255,255,0.15) 80%, rgba(255,255,255,0.45) 100%);
          -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
          -webkit-mask-composite: xor;
          mask-composite: exclude;
          pointer-events: none;
        }

        .text-glow {
          text-shadow: 0 0 40px rgba(255, 255, 255, 0.4), 0 0 80px rgba(255, 255, 255, 0.2), 0 0 120px rgba(255, 255, 255, 0.1);
        }

        .button-glow {
          box-shadow: 0 0 20px rgba(255, 255, 255, 0.3), 0 0 40px rgba(255, 255, 255, 0.1);
        }

        @keyframes spinEarth {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }

        .animate-spin-earth {
          animation: spinEarth 50s linear infinite;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">

    <!-- Top Announcement Banner -->
    @if(isset($announcementBanner) && $announcementBanner)
    <div class="bg-navy-900 text-white text-xs py-2 px-4 text-center font-medium flex items-center justify-center gap-2">
        <i class="fa-solid fa-scale-balanced text-amber-400"></i>
        <span>{{ $announcementBanner }}</span>
    </div>
    @endif

    <!-- Main Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-lg bg-navy-900 flex items-center justify-center text-amber-400 shadow-sm group-hover:bg-blue-600 transition">
                    <i class="fa-solid fa-gavel text-xl"></i>
                </div>
                <div>
                    <span class="font-extrabold text-xl tracking-tight text-navy-900 block leading-none">JURIS SENTINEL</span>
                    <span class="text-xs text-slate-500 font-medium tracking-wide">LAWYER APPOINTMENT PORTAL</span>
                </div>
            </a>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-700">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition {{ request()->routeIs('home') ? 'text-blue-600 font-bold' : '' }}">Home</a>
                <a href="{{ route('lawyers.index') }}" class="hover:text-blue-600 transition {{ request()->routeIs('lawyers.*') ? 'text-blue-600 font-bold' : '' }}">Search Lawyers</a>
                <a href="{{ route('lawyers.index', ['service' => 'criminal']) }}" class="hover:text-blue-600 transition">Criminal Law</a>
                <a href="{{ route('lawyers.index', ['service' => 'divorce']) }}" class="hover:text-blue-600 transition">Divorce & Family</a>
                <a href="{{ route('lawyers.index', ['service' => 'affidavit']) }}" class="hover:text-blue-600 transition">Affidavit</a>
                <a href="{{ route('lawyers.index', ['service' => 'civil']) }}" class="hover:text-blue-600 transition">Civil Law</a>
            </nav>

            <!-- User Auth Controls -->
            <div class="flex items-center gap-3">
                @auth
                    <div class="flex items-center gap-2">
                        <img src="{{ auth()->user()->profile_pic_url }}" alt="{{ auth()->user()->name }}"
                            class="w-9 h-9 rounded-full object-cover border-2 border-slate-300 shadow-sm">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-purple-100 text-purple-800 text-xs font-bold px-3 py-2 rounded-md hover:bg-purple-200 transition">
                                <i class="fa-solid fa-user-shield"></i> Admin Panel
                            </a>
                        @elseif(auth()->user()->isLawyer())
                            <a href="{{ route('lawyer.dashboard') }}" class="inline-flex items-center gap-2 bg-amber-100 text-amber-900 text-xs font-bold px-3 py-2 rounded-md hover:bg-amber-200 transition">
                                <i class="fa-solid fa-briefcase"></i> Lawyer Panel
                            </a>
                        @else
                            <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 text-xs font-bold px-3 py-2 rounded-md hover:bg-blue-100 transition">
                                <i class="fa-solid fa-calendar-check"></i> My Appointments
                            </a>
                        @endif
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-medium text-slate-600 hover:text-red-600 px-3 py-2 rounded-md border border-slate-200 hover:border-red-200 transition">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-blue-600 px-3 py-2 transition">
                        Log In
                    </a>
                    <div class="relative group">
                        <a href="{{ route('register.customer') }}" class="inline-flex items-center gap-2 bg-navy-900 text-white text-sm font-semibold px-4 py-2.5 rounded-md hover:bg-blue-600 transition shadow-sm">
                            <i class="fa-solid fa-user-plus text-xs"></i> Register
                        </a>
                        <div class="absolute right-0 mt-1 w-48 bg-white border border-slate-200 rounded-md shadow-lg py-1 hidden group-hover:block z-50">
                            <a href="{{ route('register.customer') }}" class="block px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                <i class="fa-solid fa-user text-blue-600 mr-2"></i> Register as Customer
                            </a>
                            <a href="{{ route('register.lawyer') }}" class="block px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                <i class="fa-solid fa-gavel text-amber-600 mr-2"></i> Register as Lawyer
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Global Notification Alerts -->
    @if(session('success'))
    <div class="bg-emerald-50 border-b border-emerald-200 text-emerald-800 text-sm py-3 px-4 text-center font-medium flex items-center justify-center gap-2">
        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-b border-red-200 text-red-800 text-sm py-3 px-4 text-center font-medium flex items-center justify-center gap-2">
        <i class="fa-solid fa-circle-exclamation text-red-600 text-base"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Content View -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-navy-900 text-slate-300 text-sm pt-12 pb-8 mt-16 border-t border-navy-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <div>
                <div class="flex items-center gap-2 mb-4 text-white">
                    <i class="fa-solid fa-gavel text-amber-400 text-xl"></i>
                    <span class="font-extrabold text-lg tracking-tight">JURIS SENTINEL</span>
                </div>
                <p class="text-slate-400 text-xs leading-relaxed">
                    Juris Sentinel is a high-trust lawyer search & appointment portal connecting citizens with qualified attorneys across Criminal, Divorce, Affidavit, and Civil legal disciplines.
                </p>
            </div>
            <div>
                <h4 class="text-white font-semibold text-xs uppercase tracking-wider mb-4">Legal Services</h4>
                <ul class="space-y-2 text-xs text-slate-400">
                    <li><a href="{{ route('lawyers.index', ['service' => 'criminal']) }}" class="hover:text-white transition">Criminal Law Defense</a></li>
                    <li><a href="{{ route('lawyers.index', ['service' => 'divorce']) }}" class="hover:text-white transition">Divorce & Custody Counsel</a></li>
                    <li><a href="{{ route('lawyers.index', ['service' => 'affidavit']) }}" class="hover:text-white transition">Affidavits & Notarization</a></li>
                    <li><a href="{{ route('lawyers.index', ['service' => 'civil']) }}" class="hover:text-white transition">Civil Litigation</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-xs uppercase tracking-wider mb-4">User Portals</h4>
                <ul class="space-y-2 text-xs text-slate-400">
                    <li><a href="{{ route('register.customer') }}" class="hover:text-white transition">Customer Registration</a></li>
                    <li><a href="{{ route('register.lawyer') }}" class="hover:text-white transition">Lawyer Registration</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white transition">Account Login</a></li>
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-white transition">Admin Management Portal</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-xs uppercase tracking-wider mb-4">Emergency Support</h4>
                <p class="text-xs text-slate-400 mb-2">Need immediate legal assistance?</p>
                <div class="text-amber-400 font-bold text-base mb-1">
                    <i class="fa-solid fa-phone mr-1"></i> +1 (800) 555-JURIS
                </div>
                <span class="text-xs text-slate-500">24/7 Verified Attorney Assistance</span>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-slate-800 pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} Juris Sentinel. Follows Stitch Lawyer Appointment Portal Design System.</p>
            <div class="flex gap-4 mt-2 sm:mt-0">
                <a href="#" class="hover:text-slate-400">Privacy Policy</a>
                <a href="#" class="hover:text-slate-400">Terms of Service</a>
                <a href="#" class="hover:text-slate-400">Bar Verification</a>
            </div>
        </div>
    </footer>

</body>
</html>
