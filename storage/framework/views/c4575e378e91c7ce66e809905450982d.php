<?php $__env->startSection('content'); ?>

<?php $profile = $lawyer->lawyerProfile; ?>

<!-- Header Banner -->
<div class="bg-navy-950 text-white py-12 px-4 border-b border-slate-800">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        
        <div class="flex items-center gap-5">
            <img src="<?php echo e($lawyer->profile_pic_url); ?>" alt="<?php echo e($lawyer->name); ?>"
                class="w-20 h-20 rounded-2xl object-cover border-4 border-amber-400 shadow-md flex-shrink-0">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight"><?php echo e($lawyer->name); ?></h1>
                    <span class="bg-emerald-500/20 text-emerald-300 text-xs font-bold px-2.5 py-0.5 rounded-full border border-emerald-500/40">
                        <i class="fa-solid fa-circle-check text-[10px] mr-1"></i> Verified Practitioner
                    </span>
                </div>
                <p class="text-slate-300 text-xs sm:text-sm mt-1">
                    <i class="fa-solid fa-location-dot text-amber-400 mr-1"></i> <?php echo e($profile->location ?? 'City'); ?> &bull; 
                    <i class="fa-solid fa-award text-amber-400 mr-1"></i> <?php echo e($profile->experience_years ?? 5); ?> Years Experience &bull; 
                    Bar License: <span class="font-mono text-slate-200"><?php echo e($profile->license_number ?? 'N/A'); ?></span>
                </p>
            </div>
        </div>

        <div class="bg-navy-900 border border-slate-700 p-4 rounded-xl text-right self-stretch md:self-auto flex flex-row md:flex-col justify-between items-center md:items-end">
            <span class="text-xs text-slate-400">Consultation Fee</span>
            <span class="text-2xl font-extrabold text-amber-400">$<?php echo e(number_format($profile->hourly_fee ?? 150, 2)); ?><span class="text-xs font-normal text-slate-300"> / hr</span></span>
        </div>

    </div>
</div>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Detailed Lawyer Profile -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Biography Card -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="font-extrabold text-base text-navy-900 mb-3 pb-2 border-b border-slate-150 flex items-center gap-2">
                    <i class="fa-solid fa-id-card text-blue-600"></i> Attorney Biography
                </h3>
                <p class="text-slate-600 text-xs leading-relaxed whitespace-pre-line">
                    <?php echo e($profile->bio); ?>

                </p>
            </div>

            <!-- Specialization Practice Areas -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="font-extrabold text-base text-navy-900 mb-3 pb-2 border-b border-slate-150 flex items-center gap-2">
                    <i class="fa-solid fa-gavel text-amber-600"></i> Specializations Offered
                </h3>
                <div class="space-y-2">
                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-lg border border-slate-200 text-xs">
                        <span class="font-bold text-slate-800"><?php echo e($svc->name); ?></span>
                        <span class="text-blue-600 font-medium">Available</span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Meeting Format Options -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="font-extrabold text-base text-navy-900 mb-3 pb-2 border-b border-slate-150 flex items-center gap-2">
                    <i class="fa-solid fa-building text-emerald-600"></i> Consultation Formats
                </h3>
                <p class="text-xs text-slate-600 font-medium bg-slate-50 p-3 rounded-lg border border-slate-200">
                    <i class="fa-solid fa-check text-emerald-600 mr-1.5"></i> <?php echo e($profile->meeting_place); ?>

                </p>
            </div>

        </div>

        <!-- Right Column: Interactive Slot-Based Appointment Booking Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-150 mb-6">
                    <div>
                        <h2 class="text-xl font-extrabold text-navy-900">Schedule Consultation Meeting</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Select a date and book an available time slot</p>
                    </div>
                    <span class="text-xs bg-blue-50 text-blue-700 font-bold px-3 py-1 rounded-full border border-blue-200">
                        <i class="fa-solid fa-clock mr-1"></i> Slot Duration: <?php echo e($schedule->slot_duration_minutes ?? 30); ?> Mins
                    </span>
                </div>

                <!-- Step 1: Select Date -->
                <div class="mb-8 bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Select Appointment Date</label>
                    <div class="flex gap-3">
                        <input type="date" id="date_picker" value="<?php echo e($selectedDate); ?>" min="<?php echo e(date('Y-m-d')); ?>"
                            class="bg-white border border-slate-300 rounded-lg px-4 py-2 text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-600 flex-1">
                        <button type="button" id="btn_fetch_slots" onclick="fetchSlotsForDate()" class="bg-navy-900 hover:bg-blue-600 text-white font-bold px-5 py-2 rounded-lg text-xs transition flex items-center gap-1.5">
                            <i class="fa-solid fa-arrows-rotate text-[10px]"></i> Check Available Slots
                        </button>
                    </div>
                    <div class="text-[11px] text-slate-500 mt-2 flex items-center justify-between">
                        <span>Currently viewing slots for: <strong id="lbl_selected_date" class="text-slate-800"><?php echo e(date('D, M j, Y', strtotime($selectedDate))); ?></strong></span>
                        <span id="slot_loading_status" class="text-blue-600 font-semibold hidden"><i class="fa-solid fa-circle-notch fa-spin mr-1"></i> Fetching slots...</span>
                    </div>
                </div>

                <!-- Booking Submission Form -->
                <form id="booking_form" action="<?php echo e(route('appointments.book')); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="lawyer_id" value="<?php echo e($lawyer->id); ?>">
                    <input type="hidden" name="appointment_date" id="form_appointment_date" value="<?php echo e($selectedDate); ?>">

                    <!-- Notification Box -->
                    <div id="booking_response_box" class="hidden p-4 rounded-lg text-xs font-semibold"></div>

                    <!-- Step 2: Available Slots Picker -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-3">Select Available Time Slot</label>
                        
                        <div id="slots_container">
                            <?php if(empty($availableSlots)): ?>
                                <div class="bg-amber-50 border border-amber-200 text-amber-800 text-xs p-4 rounded-lg">
                                    <i class="fa-solid fa-calendar-xmark mr-1.5 text-amber-600"></i> No available consultation slots on this date (Attorney non-working day or fully booked). Please select another date above.
                                </div>
                            <?php else: ?>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                    <?php $__currentLoopData = $availableSlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($slot['is_booked']): ?>
                                            <div class="bg-slate-100 border border-slate-200 text-slate-400 p-3 rounded-lg text-center cursor-not-allowed opacity-60">
                                                <span class="block font-semibold text-xs"><?php echo e($slot['display']); ?></span>
                                                <span class="text-[10px] text-red-500 font-bold uppercase">Booked</span>
                                            </div>
                                        <?php else: ?>
                                            <label class="relative border border-slate-200 rounded-lg p-3 text-center cursor-pointer hover:border-blue-600 hover:bg-blue-50/50 transition block has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-600">
                                                <input type="radio" name="slot" value="<?php echo e($slot['start']); ?>|<?php echo e($slot['end']); ?>" required class="sr-only" <?php echo e($index === 0 ? 'checked' : ''); ?> onchange="updateSelectedSlot('<?php echo e($slot['start']); ?>', '<?php echo e($slot['end']); ?>')">
                                                <span class="block font-bold text-xs text-navy-900"><?php echo e($slot['display']); ?></span>
                                                <span class="text-[10px] text-emerald-600 font-medium"><i class="fa-solid fa-circle text-[8px] mr-1"></i> Available</span>
                                            </label>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <input type="hidden" name="start_time" id="input_start_time" value="<?php echo e($availableSlots[0]['start'] ?? ''); ?>">
                        <input type="hidden" name="end_time" id="input_end_time" value="<?php echo e($availableSlots[0]['end'] ?? ''); ?>">
                    </div>

                    <!-- Step 3: Select Legal Service Needed -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Select Legal Service / Matter</label>
                            <select name="service_id" required class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 bg-white">
                                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($svc->id); ?>"><?php echo e($svc->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Meeting Place / Format</label>
                            <select name="meeting_place" required class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 bg-white">
                                <option value="In-Person Office">In-Person Office</option>
                                <option value="Virtual Video Call">Virtual Video Call</option>
                            </select>
                        </div>
                    </div>

                    <!-- Step 4: Requirement Notes -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Case Requirement Details / Notes</label>
                        <textarea name="notes" rows="3" class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600"
                            placeholder="Briefly state your case issue (e.g. Affidavit verification, custody disagreement, civil dispute)..."></textarea>
                    </div>

                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->isCustomer()): ?>
                            <button type="submit" id="btn_submit_booking" class="w-full bg-blue-600 hover:bg-navy-900 text-white font-bold py-3.5 rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2">
                                <i class="fa-solid fa-calendar-check"></i> Confirm & Book Consultation Slot
                            </button>
                        <?php else: ?>
                            <div class="bg-amber-50 text-amber-900 text-xs p-4 rounded-lg font-medium border border-amber-200 text-center">
                                You are currently logged in as a <?php echo e(ucfirst(auth()->user()->role)); ?>. Only customer accounts can book slots.
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="bg-slate-100 p-4 rounded-xl border border-slate-200 text-center space-y-2">
                            <p class="text-xs font-medium text-slate-700">You must be logged in as a Customer to book an appointment slot.</p>
                            <div class="flex justify-center gap-3">
                                <a href="<?php echo e(route('login')); ?>" class="bg-navy-900 text-white font-bold text-xs px-4 py-2 rounded-lg hover:bg-blue-600 transition">Log In to Book</a>
                                <a href="<?php echo e(route('register.customer')); ?>" class="bg-slate-200 text-slate-800 font-bold text-xs px-4 py-2 rounded-lg hover:bg-slate-300 transition">Register Customer Account</a>
                            </div>
                        </div>
                    <?php endif; ?>

                </form>

            </div>
        </div>

    </div>
</div>

<script>
    const lawyerId = <?php echo e($lawyer->id); ?>;

    function updateSelectedSlot(start, end) {
        document.getElementById('input_start_time').value = start;
        document.getElementById('input_end_time').value = end;
    }

    // Interactive AJAX Slot Fetching
    document.getElementById('date_picker').addEventListener('change', function() {
        fetchSlotsForDate();
    });

    async function fetchSlotsForDate() {
        const selectedDate = document.getElementById('date_picker').value;
        if (!selectedDate) return;

        document.getElementById('form_appointment_date').value = selectedDate;
        document.getElementById('slot_loading_status').classList.remove('hidden');

        try {
            const response = await fetch(`/api/lawyers/${lawyerId}/slots?date=${selectedDate}`);
            const data = await response.json();

            document.getElementById('lbl_selected_date').textContent = data.date_formatted;
            const container = document.getElementById('slots_container');

            if (!data.is_working_day || data.slots.length === 0) {
                container.innerHTML = `
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 text-xs p-4 rounded-lg animate-fade-in">
                        <i class="fa-solid fa-calendar-xmark mr-1.5 text-amber-600"></i> No available consultation slots on this date (Attorney non-working day or fully booked). Please select another date.
                    </div>
                `;
                document.getElementById('input_start_time').value = '';
                document.getElementById('input_end_time').value = '';
            } else {
                let html = `<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 animate-fade-in">`;
                data.slots.forEach((slot, index) => {
                    if (slot.is_booked) {
                        html += `
                            <div class="bg-slate-100 border border-slate-200 text-slate-400 p-3 rounded-lg text-center cursor-not-allowed opacity-60">
                                <span class="block font-semibold text-xs">${slot.display}</span>
                                <span class="text-[10px] text-red-500 font-bold uppercase">Booked</span>
                            </div>
                        `;
                    } else {
                        const checked = index === 0 ? 'checked' : '';
                        html += `
                            <label class="relative border border-slate-200 rounded-lg p-3 text-center cursor-pointer hover:border-blue-600 hover:bg-blue-50/50 transition block has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-600">
                                <input type="radio" name="slot" value="${slot.start}|${slot.end}" required class="sr-only" ${checked} onchange="updateSelectedSlot('${slot.start}', '${slot.end}')">
                                <span class="block font-bold text-xs text-navy-900">${slot.display}</span>
                                <span class="text-[10px] text-emerald-600 font-medium"><i class="fa-solid fa-circle text-[8px] mr-1"></i> Available</span>
                            </label>
                        `;
                    }
                });
                html += `</div>`;
                container.innerHTML = html;

                // Select first available slot
                const availableSlot = data.slots.find(s => !s.is_booked);
                if (availableSlot) {
                    updateSelectedSlot(availableSlot.start, availableSlot.end);
                }
            }
        } catch (err) {
            console.error("Error fetching slots:", err);
        } finally {
            document.getElementById('slot_loading_status').classList.add('hidden');
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\A.r Computer's\OneDrive\Videos\eProject Lawyers Website\resources\views/lawyers/show.blade.php ENDPATH**/ ?>