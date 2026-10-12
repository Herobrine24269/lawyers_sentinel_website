<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Welcome, <?php echo e($lawyer->name); ?></h1>
            <p class="text-xs text-slate-500 mt-1">Lawyer Panel & Consultation Dashboard</p>
        </div>
        <a href="<?php echo e(route('lawyer.schedule')); ?>" class="bg-blue-600 hover:bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-lg transition inline-flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-clock"></i> Manage Availability Slots
        </a>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Bookings</span>
            <span class="text-3xl font-extrabold text-slate-900"><?php echo e(count($appointments)); ?></span>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-amber-600 uppercase tracking-wider block mb-1">Pending Requests</span>
            <span class="text-3xl font-extrabold text-amber-600"><?php echo e($pendingCount); ?></span>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider block mb-1">Confirmed Meetings</span>
            <span class="text-3xl font-extrabold text-emerald-600"><?php echo e($confirmedCount); ?></span>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">Completed</span>
            <span class="text-3xl font-extrabold text-blue-600"><?php echo e($completedCount); ?></span>
        </div>
    </div>

    <!-- Recent Appointment Requests Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-150 flex items-center justify-between">
            <h2 class="font-extrabold text-base text-slate-900">Recent Customer Booking Requests</h2>
            <a href="<?php echo e(route('lawyer.appointments')); ?>" class="text-xs font-bold text-blue-600 hover:underline">View All Requests</a>
        </div>

        <?php if($appointments->isEmpty()): ?>
            <div class="p-10 text-center text-xs text-slate-500">
                No customer appointment requests received yet.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Customer Name</th>
                            <th class="px-6 py-3.5">Service Requested</th>
                            <th class="px-6 py-3.5">Date & Time Slot</th>
                            <th class="px-6 py-3.5">Meeting Format</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Quick Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150 font-medium">
                        <?php $__currentLoopData = $appointments->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $apt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900"><?php echo e($apt->customer->name ?? 'Customer'); ?></div>
                                <div class="text-[11px] text-slate-500"><?php echo e($apt->customer->email ?? ''); ?></div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800"><?php echo e($apt->service->name ?? 'General'); ?></td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900"><?php echo e(date('M j, Y', strtotime($apt->appointment_date))); ?></div>
                                <div class="text-[11px] text-slate-500"><?php echo e(date('g:i A', strtotime($apt->start_time))); ?> - <?php echo e(date('g:i A', strtotime($apt->end_time))); ?></div>
                            </td>
                            <td class="px-6 py-4"><?php echo e($apt->meeting_place); ?></td>
                            <td class="px-6 py-4">
                                <?php if($apt->status === 'confirmed'): ?>
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-300">Confirmed</span>
                                <?php elseif($apt->status === 'pending'): ?>
                                    <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded border border-amber-300">Pending</span>
                                <?php elseif($apt->status === 'completed'): ?>
                                    <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-300">Completed</span>
                                <?php else: ?>
                                    <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2 py-0.5 rounded border border-red-300">Cancelled</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="<?php echo e(route('lawyer.appointments')); ?>" class="text-xs text-blue-600 font-bold hover:underline">
                                    Manage Request
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lawyer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\A.r Computer's\OneDrive\Videos\eProject Lawyers Website\resources\views/lawyer/dashboard.blade.php ENDPATH**/ ?>