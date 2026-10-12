<?php $__env->startSection('content'); ?>

<!-- Header Banner -->
<div class="bg-navy-900 text-white py-12 px-4 border-b border-slate-800">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Search & Find Lawyers</h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-1">
                Showing minimal info cards of certified attorneys available for booking
            </p>
        </div>
        <div class="text-xs bg-navy-800 text-amber-400 font-bold px-3 py-1.5 rounded-lg border border-slate-700 self-start md:self-auto">
            <i class="fa-solid fa-user-shield mr-1"></i> <?php echo e(count($lawyers)); ?> Attorneys Found
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Filter Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm sticky top-24">
                <h3 class="font-bold text-sm text-navy-900 mb-4 pb-2 border-b border-slate-150 flex items-center justify-between">
                    <span><i class="fa-solid fa-filter text-blue-600 mr-2"></i> Filter Lawyers</span>
                    <a href="<?php echo e(route('lawyers.index')); ?>" class="text-[11px] font-normal text-blue-600 hover:underline">Reset All</a>
                </h3>

                <form action="<?php echo e(route('lawyers.index')); ?>" method="GET" class="space-y-4 text-xs">
                    
                    <!-- Search Keyword -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Lawyer Name</label>
                        <input type="text" name="q" value="<?php echo e($keyword); ?>" placeholder="Search by name..."
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600">
                    </div>

                    <!-- Service Category -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Specialization Service</label>
                        <select name="service" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 bg-white">
                            <option value="">All Specializations</option>
                            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($svc->slug); ?>" <?php echo e(($serviceFilter == $svc->slug || $serviceFilter == $svc->id) ? 'selected' : ''); ?>>
                                    <?php echo e($svc->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Location Filter -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Lawyer Location</label>
                        <input type="text" name="location" value="<?php echo e($locationFilter); ?>" placeholder="City or State..."
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600">
                    </div>

                    <!-- Meeting Format -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Meeting Place</label>
                        <select name="meeting_place" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 bg-white">
                            <option value="">Any Format</option>
                            <option value="Office" <?php echo e($meetingPlaceFilter == 'Office' ? 'selected' : ''); ?>>In-Person Office</option>
                            <option value="Virtual" <?php echo e($meetingPlaceFilter == 'Virtual' ? 'selected' : ''); ?>>Virtual Video Call</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-navy-900 hover:bg-blue-600 text-white font-bold py-2.5 rounded-lg text-xs transition shadow-sm">
                        Apply Filters
                    </button>
                </form>
            </div>
        </div>

        <!-- Lawyer Cards List (Showcase) -->
        <div class="lg:col-span-3 space-y-6">
            <div id="lawyers_results_container" class="space-y-6">
                <?php if(count($lawyers) === 0): ?>
                    <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                            <i class="fa-solid fa-user-slash"></i>
                        </div>
                        <h3 class="font-bold text-lg text-navy-900">No Lawyers Found</h3>
                        <p class="text-slate-500 text-xs mt-1">Try adjusting your location, specialization, or keyword filter criteria.</p>
                        <a href="<?php echo e(route('lawyers.index')); ?>" class="inline-block mt-4 text-xs font-semibold text-blue-600 hover:underline">
                            View All Available Lawyers
                        </a>
                    </div>
                <?php else: ?>
                    <?php $__currentLoopData = $lawyers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lawyer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $profile = $lawyer->lawyerProfile; ?>
                    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">
                        
                        <div class="flex gap-4 items-start flex-1">
                            <!-- Avatar Image -->
                            <img src="<?php echo e($lawyer->profile_pic_url); ?>" alt="<?php echo e($lawyer->name); ?>"
                                class="w-16 h-16 rounded-xl object-cover border-2 border-amber-400 shadow-sm flex-shrink-0">

                            <!-- Minimal Lawyer Info -->
                            <div class="space-y-1.5 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="font-extrabold text-lg text-navy-900"><?php echo e($lawyer->name); ?></h3>
                                    <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-200">
                                        <i class="fa-solid fa-shield-check mr-1"></i> Verified
                                    </span>
                                </div>

                                <div class="flex items-center gap-4 text-xs text-slate-600 flex-wrap">
                                    <span><i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> <?php echo e($profile->location ?? 'City'); ?></span>
                                    <span><i class="fa-solid fa-award text-amber-500 mr-1"></i> <?php echo e($profile->experience_years ?? 5); ?> Yrs Experience</span>
                                    <span><i class="fa-solid fa-building-columns text-slate-400 mr-1"></i> License: <?php echo e($profile->license_number ?? 'N/A'); ?></span>
                                </div>

                                <!-- Specializations Badges -->
                                <div class="flex items-center gap-1.5 flex-wrap pt-1">
                                    <?php if($profile && $profile->specializations): ?>
                                        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(in_array($svc->id, $profile->specializations ?? [])): ?>
                                                <span class="bg-slate-100 text-slate-700 text-[10px] font-semibold px-2.5 py-0.5 rounded-full border border-slate-200">
                                                    <?php echo e($svc->name); ?>

                                                </span>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>

                                <p class="text-xs text-slate-500 line-clamp-2 pt-1 leading-relaxed">
                                    <?php echo e($profile->bio); ?>

                                </p>
                            </div>
                        </div>

                        <!-- Right Column: Minimal Info & Book CTA -->
                        <div class="w-full md:w-56 border-t md:border-t-0 md:border-l border-slate-150 pt-4 md:pt-0 md:pl-6 flex flex-col justify-between self-stretch">
                            <div class="space-y-2 mb-4">
                                <div class="text-xs flex justify-between items-center">
                                    <span class="text-slate-500">Consultation Fee</span>
                                    <span class="font-extrabold text-blue-600 text-sm">$<?php echo e(number_format($profile->hourly_fee ?? 150, 2)); ?>/hr</span>
                                </div>
                                <div class="text-xs flex justify-between items-center">
                                    <span class="text-slate-500">Rating</span>
                                    <span class="font-bold text-slate-800"><i class="fa-solid fa-star text-amber-500 text-[10px]"></i> <?php echo e($profile->rating ?? '5.0'); ?></span>
                                </div>
                                <div class="text-xs flex justify-between items-center">
                                    <span class="text-slate-500">Meeting Format</span>
                                    <span class="font-medium text-slate-700 text-[11px] truncate max-w-[110px]" title="<?php echo e($profile->meeting_place); ?>">
                                        <?php echo e($profile->meeting_place); ?>

                                    </span>
                                </div>
                            </div>

                            <a href="<?php echo e(route('lawyers.show', $lawyer->id)); ?>" class="w-full bg-navy-900 hover:bg-blue-600 text-white font-bold py-2.5 px-3 rounded-lg text-xs transition shadow-sm text-center flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-calendar-check text-[11px]"></i> View Profile & Book Slot
                            </a>
                        </div>

                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script>
    let searchDebounceTimeout = null;

    const inputQ = document.querySelector('input[name="q"]');
    const selectService = document.querySelector('select[name="service"]');
    const inputLocation = document.querySelector('input[name="location"]');
    const selectMeeting = document.querySelector('select[name="meeting_place"]');
    const resultsContainer = document.getElementById('lawyers_results_container');
    const counterBadge = document.querySelector('.bg-navy-800');

    [inputQ, inputLocation].forEach(input => {
        if (input) {
            input.addEventListener('input', () => {
                clearTimeout(searchDebounceTimeout);
                searchDebounceTimeout = setTimeout(performLiveSearch, 300);
            });
        }
    });

    [selectService, selectMeeting].forEach(select => {
        if (select) {
            select.addEventListener('change', performLiveSearch);
        }
    });

    async function performLiveSearch() {
        const q = inputQ ? inputQ.value : '';
        const service = selectService ? selectService.value : '';
        const location = inputLocation ? inputLocation.value : '';
        const meeting_place = selectMeeting ? selectMeeting.value : '';

        const params = new URLSearchParams({ q, service, location, meeting_place });

        try {
            const response = await fetch(`/api/lawyers/search?${params.toString()}`);
            const data = await response.json();

            if (counterBadge) {
                counterBadge.innerHTML = `<i class="fa-solid fa-user-shield mr-1"></i> ${data.count} Attorneys Found`;
            }

            if (data.count === 0) {
                resultsContainer.innerHTML = `
                    <div class="bg-white rounded-xl border border-slate-200 p-12 text-center animate-fade-in">
                        <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                            <i class="fa-solid fa-user-slash"></i>
                        </div>
                        <h3 class="font-bold text-lg text-navy-900">No Lawyers Found</h3>
                        <p class="text-slate-500 text-xs mt-1">Try adjusting your location, specialization, or keyword filter criteria.</p>
                    </div>
                `;
            } else {
                let html = '';
                data.lawyers.forEach(l => {
                    let specsHtml = l.specializations.map(s => `<span class="bg-slate-100 text-slate-700 text-[10px] font-semibold px-2.5 py-0.5 rounded-full border border-slate-200">${s}</span>`).join(' ');

                    html += `
                        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition flex flex-col md:flex-row gap-6 items-start md:items-center justify-between animate-fade-in">
                            <div class="flex gap-4 items-start flex-1">
                                <img src="${l.profile_pic_url}" alt="${l.name}"
                                    class="w-16 h-16 rounded-xl object-cover border-2 border-amber-400 shadow-sm flex-shrink-0">
                                <div class="space-y-1.5 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="font-extrabold text-lg text-navy-900">${l.name}</h3>
                                        <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-200">
                                            <i class="fa-solid fa-shield-check mr-1"></i> Verified
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-4 text-xs text-slate-600 flex-wrap">
                                        <span><i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> ${l.location}</span>
                                        <span><i class="fa-solid fa-award text-amber-500 mr-1"></i> ${l.experience_years} Yrs Experience</span>
                                        <span><i class="fa-solid fa-building-columns text-slate-400 mr-1"></i> License: ${l.license_number}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 flex-wrap pt-1">
                                        ${specsHtml}
                                    </div>
                                    <p class="text-xs text-slate-500 line-clamp-2 pt-1 leading-relaxed">${l.bio}</p>
                                </div>
                            </div>
                            <div class="w-full md:w-56 border-t md:border-t-0 md:border-l border-slate-150 pt-4 md:pt-0 md:pl-6 flex flex-col justify-between self-stretch">
                                <div class="space-y-2 mb-4">
                                    <div class="text-xs flex justify-between items-center">
                                        <span class="text-slate-500">Consultation Fee</span>
                                        <span class="font-extrabold text-blue-600 text-sm">$${l.hourly_fee}/hr</span>
                                    </div>
                                    <div class="text-xs flex justify-between items-center">
                                        <span class="text-slate-500">Rating</span>
                                        <span class="font-bold text-slate-800"><i class="fa-solid fa-star text-amber-500 text-[10px]"></i> ${l.rating}</span>
                                    </div>
                                    <div class="text-xs flex justify-between items-center">
                                        <span class="text-slate-500">Meeting Format</span>
                                        <span class="font-medium text-slate-700 text-[11px] truncate max-w-[110px]" title="${l.meeting_place}">${l.meeting_place}</span>
                                    </div>
                                </div>
                                <a href="${l.profile_url}" class="w-full bg-navy-900 hover:bg-blue-600 text-white font-bold py-2.5 px-3 rounded-lg text-xs transition shadow-sm text-center flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-calendar-check text-[11px]"></i> View Profile & Book Slot
                                </a>
                            </div>
                        </div>
                    `;
                });
                resultsContainer.innerHTML = html;
            }
        } catch (err) {
            console.error("Live search failed:", err);
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\A.r Computer's\OneDrive\Videos\eProject Lawyers Website\resources\views/lawyers/index.blade.php ENDPATH**/ ?>