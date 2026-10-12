@extends('layouts.app', ['announcementBanner' => $content->announcement_banner ?? ''])

@section('content')

<!-- Hero Section (Stitch Juris Sentinel Design) -->
<section class="bg-navy-950 text-white relative py-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Subtle Background Glow -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-amber-600/10 rounded-full blur-3xl"></div>

    <div class="max-w-6xl mx-auto text-center relative z-10">
        
        <!-- Trust Pill Badge -->
        <div class="inline-flex items-center gap-2 bg-navy-900 border border-slate-700/80 rounded-full px-4 py-1.5 mb-6 text-xs text-slate-300 font-medium">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
            Verified Attorney Appointment Platform
        </div>

        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 leading-tight">
            {{ $content->hero_title ?? 'Find & Book Trusted Legal Representation' }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto mb-10 leading-relaxed">
            {{ $content->hero_subtitle ?? 'Search top-rated attorneys by specialization and city. View availability slots and schedule your consultation in minutes.' }}
        </p>

        <!-- Search Bar Card -->
        <div class="bg-white rounded-xl shadow-xl p-4 sm:p-5 text-slate-800 max-w-4xl mx-auto border border-slate-200">
            <form action="{{ route('lawyers.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-left">
                
                <!-- Service Dropdown -->
                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1 tracking-wider">Legal Service</label>
                    <div class="relative">
                        <select name="service" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-600 transition appearance-none">
                            <option value="">All Services</option>
                            @foreach($services as $svc)
                                <option value="{{ $svc->slug }}">{{ $svc->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-3.5 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>

                <!-- Location Input -->
                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1 tracking-wider">Location / City</label>
                    <div class="relative">
                        <input type="text" name="location" placeholder="e.g. New York, CA" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-600 transition">
                        <i class="fa-solid fa-location-dot absolute right-3 top-3.5 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <!-- Meeting Type -->
                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1 tracking-wider">Meeting Place</label>
                    <div class="relative">
                        <select name="meeting_place" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-600 transition appearance-none">
                            <option value="">Any Format</option>
                            <option value="Office">In-Person Office</option>
                            <option value="Virtual">Virtual Video Call</option>
                        </select>
                        <i class="fa-solid fa-video absolute right-3 top-3.5 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-navy-900 text-white font-bold py-2.5 px-4 rounded-lg text-sm transition shadow-sm flex items-center justify-center gap-2 h-[42px]">
                        <i class="fa-solid fa-magnifying-glass"></i> Search Lawyers
                    </button>
                </div>

            </form>
        </div>

    </div>
</section>

<!-- Legal Specializations Categories Section (Serene Earth Rotating Space Background) -->
<section id="specialization-section" class="relative overflow-hidden py-28 px-4 sm:px-6 lg:px-8 text-white min-h-[650px] flex flex-col justify-center bg-[#0a0608]">
    
    <!-- Background Rotating Earth Video (Autoplay, Loop, Muted, Playsinline) -->
    <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover z-0 pointer-events-none opacity-90">
        <source src="https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260613_180732_a54afbf6-b30d-470e-861f-669871f09f67.mp4" type="video/mp4">
    </video>

    <!-- Dark Ambient Vignette Overlay -->
    <div class="absolute inset-0 bg-gradient-to-b from-[#0a0608]/80 via-black/40 to-[#0a0608]/90 z-0 pointer-events-none"></div>

    <!-- Parallax Layer: Left Cloud Image -->
    <img id="bg_cloud_left" src="https://soft-zoom-63098134.figma.site/_assets/v11/0d6dfd3f90b930f21726f2ed56a3320d79b7a797.png"
        alt="Background Cloud Left"
        class="absolute left-0 bottom-[2%] z-10 w-[500px] md:w-[650px] pointer-events-none hidden sm:block opacity-0 transition-transform ease-out will-change-transform"
        style="margin-left: -20%; transform: translate3d(-200px, 0, 0);">

    <!-- Parallax Layer: Right Cloud Image (Flipped) -->
    <img id="bg_cloud_right" src="https://soft-zoom-63098134.figma.site/_assets/v11/0d6dfd3f90b930f21726f2ed56a3320d79b7a797.png"
        alt="Background Cloud Right"
        class="absolute right-0 bottom-[5%] z-10 w-[500px] md:w-[650px] pointer-events-none hidden sm:block opacity-0 transition-transform ease-out will-change-transform scale-x-[-1]"
        style="margin-right: -30%; transform: translate3d(200px, 0, 0);">

    <!-- Section Content (z-20 to sit above video/clouds & remain fully interactive) -->
    <div class="max-w-7xl mx-auto relative z-20 w-full">
        <div class="text-center mb-14">
            <h2 class="text-3xl sm:text-6xl font-extrabold text-white tracking-tight text-glow font-instrument">Legal Specialization Practice Areas</h2>
            <p class="text-white/80 text-sm sm:text-base mt-3 max-w-xl mx-auto font-inter">Select your required service category to find specialized attorneys</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($services as $svc)
            <a href="{{ route('lawyers.index', ['service' => $svc->slug]) }}" class="group liquid-glass bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:border-amber-300 hover:bg-white/20 hover:shadow-2xl transition duration-300">
                <div class="w-12 h-12 rounded-xl bg-amber-400/20 text-amber-300 flex items-center justify-center text-xl mb-4 group-hover:bg-amber-400 group-hover:text-slate-950 transition duration-300">
                    @if($svc->slug === 'criminal')
                        <i class="fa-solid fa-shield-halved"></i>
                    @elseif($svc->slug === 'divorce')
                        <i class="fa-solid fa-people-roof"></i>
                    @elseif($svc->slug === 'affidavit')
                        <i class="fa-solid fa-file-contract"></i>
                    @elseif($svc->slug === 'civil')
                        <i class="fa-solid fa-scale-balanced"></i>
                    @else
                        <i class="fa-solid fa-gavel"></i>
                    @endif
                </div>
                <h3 class="font-bold text-xl text-white mb-2 group-hover:text-amber-300 transition duration-300">{{ $svc->name }}</h3>
                <p class="text-white/80 text-xs leading-relaxed mb-4 font-inter">{{ Str::limit($svc->description, 90) }}</p>
                <span class="text-xs font-bold text-amber-300 flex items-center gap-1 group-hover:gap-2 transition duration-300">
                    Explore Lawyers <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </span>
            </a>
            @endforeach
        </div>
    </div>

</section>

<!-- Lawyer Showcase Section (Minimal Info Cards) -->
<section class="bg-slate-100 py-16 px-4 border-y border-slate-200">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Verified Practitioners</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-navy-900 tracking-tight mt-1">Showcase of Top Lawyers</h2>
            </div>
            <a href="{{ route('lawyers.index') }}" class="text-sm font-semibold text-blue-600 hover:text-navy-900 mt-2 sm:mt-0 flex items-center gap-1">
                View All Lawyers <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredLawyers as $lawyer)
            @php $profile = $lawyer->lawyerProfile; @endphp
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                
                <div class="p-5">
                    <!-- Minimal Header -->
                    <div class="flex items-start justify-between mb-4">
                        <img src="{{ $lawyer->profile_pic_url }}" alt="{{ $lawyer->name }}"
                            class="w-14 h-14 rounded-full object-cover border-2 border-amber-400 shadow-sm flex-shrink-0">
                        <span class="bg-emerald-50 text-emerald-700 text-[11px] font-bold px-2.5 py-1 rounded-full border border-emerald-200">
                            <i class="fa-solid fa-circle-check text-[10px] mr-1"></i> Approved
                        </span>
                    </div>

                    <h3 class="font-extrabold text-base text-navy-900 mb-1 leading-snug">{{ $lawyer->name }}</h3>
                    
                    <p class="text-xs text-slate-500 flex items-center gap-1.5 mb-3">
                        <i class="fa-solid fa-location-dot text-slate-400"></i> {{ $profile->location ?? 'N/A' }}
                    </p>

                    <!-- Rating & Experience Minimal Info -->
                    <div class="flex items-center gap-4 text-xs mb-4 py-2 px-3 bg-slate-50 rounded-lg border border-slate-150">
                        <div>
                            <span class="text-slate-400 block text-[10px]">Rating</span>
                            <span class="font-bold text-slate-800 flex items-center gap-1">
                                <i class="fa-solid fa-star text-amber-500 text-[10px]"></i> {{ $profile->rating ?? '5.0' }}
                            </span>
                        </div>
                        <div class="border-r border-slate-200 h-6"></div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Experience</span>
                            <span class="font-bold text-slate-800">{{ $profile->experience_years ?? 5 }} Yrs</span>
                        </div>
                        <div class="border-r border-slate-200 h-6"></div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Hourly Rate</span>
                            <span class="font-bold text-blue-600">${{ number_format($profile->hourly_fee ?? 150, 0) }}/hr</span>
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed mb-4">
                        {{ $profile->bio ?? 'Experienced advocate providing expert counsel and consultation.' }}
                    </p>
                </div>

                <div class="px-5 pb-5 pt-0 border-t border-slate-100 mt-auto">
                    <a href="{{ route('lawyers.show', $lawyer->id) }}" class="w-full mt-3 bg-navy-900 hover:bg-blue-600 text-white font-bold py-2.5 px-3 rounded-lg text-xs transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-calendar-plus"></i> View Profile & Book Slot
                    </a>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>

<!-- Platform Statistics Banner -->
<section class="py-16 px-4 bg-navy-900 text-white">
    <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        <div>
            <div class="text-3xl sm:text-4xl font-extrabold text-amber-400 mb-1">{{ $totalLawyers }}+</div>
            <div class="text-xs text-slate-400 font-medium uppercase tracking-wider">Verified Lawyers</div>
        </div>
        <div>
            <div class="text-3xl sm:text-4xl font-extrabold text-amber-400 mb-1">{{ $content->total_cases_count ?? 4800 }}+</div>
            <div class="text-xs text-slate-400 font-medium uppercase tracking-wider">Consultations Delivered</div>
        </div>
        <div>
            <div class="text-3xl sm:text-4xl font-extrabold text-amber-400 mb-1">{{ $totalAppointments }}+</div>
            <div class="text-xs text-slate-400 font-medium uppercase tracking-wider">Slots Scheduled</div>
        </div>
        <div>
            <div class="text-3xl sm:text-4xl font-extrabold text-amber-400 mb-1">{{ $content->satisfaction_rate ?? '99%' }}</div>
            <div class="text-xs text-slate-400 font-medium uppercase tracking-wider">Client Satisfaction</div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const specSection = document.getElementById('specialization-section');
        const cloudLeft = document.getElementById('bg_cloud_left');
        const cloudRight = document.getElementById('bg_cloud_right');

        if (!specSection) return;

        let currentCloudLeftX = -200;
        let targetCloudLeftX = -200;

        let currentCloudRightX = 200;
        let targetCloudRightX = 200;

        let currentOpacity = 0;
        let targetOpacity = 0;

        function updateParallaxTargets() {
            const rect = specSection.getBoundingClientRect();
            const windowHeight = window.innerHeight || document.documentElement.clientHeight;

            let progress = (windowHeight - rect.top) / (windowHeight + rect.height);
            progress = Math.max(0, Math.min(1, progress));

            if (progress > 0.12 && progress < 0.92) {
                targetCloudLeftX = 0;
                targetCloudRightX = 0;
                targetOpacity = 0.9;
            } else {
                targetCloudLeftX = -200;
                targetCloudRightX = 200;
                targetOpacity = 0;
            }
        }

        function lerp(start, end, factor) {
            return start + (end - start) * factor;
        }

        function animateParallax() {
            updateParallaxTargets();

            currentCloudLeftX = lerp(currentCloudLeftX, targetCloudLeftX, 0.04);
            currentCloudRightX = lerp(currentCloudRightX, targetCloudRightX, 0.04);
            if (cloudLeft) {
                cloudLeft.style.transform = `translate3d(${currentCloudLeftX.toFixed(2)}px, 0, 0)`;
                cloudLeft.style.opacity = currentOpacity.toFixed(3);
            }

            if (cloudRight) {
                cloudRight.style.transform = `translate3d(${currentCloudRightX.toFixed(2)}px, 0, 0) scaleX(-1)`;
                cloudRight.style.opacity = currentOpacity.toFixed(3);
            }

            requestAnimationFrame(animateParallax);
        }

        window.addEventListener('scroll', updateParallaxTargets, { passive: true });
        requestAnimationFrame(animateParallax);
    });
</script>

@endsection
