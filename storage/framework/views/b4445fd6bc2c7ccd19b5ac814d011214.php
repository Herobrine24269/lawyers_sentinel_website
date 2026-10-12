<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Admin Control Center</h1>
            <p class="text-xs text-slate-500 mt-1">System-wide management for lawyers, services, appointment slots, and homepage</p>
        </div>
        <span class="bg-purple-100 text-purple-800 text-xs font-bold px-3 py-1.5 rounded-lg border border-purple-200">
            Juris Sentinel Admin
        </span>
    </div>

    <!-- Admin Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Lawyers</div>
            <div class="text-3xl font-extrabold text-slate-900"><?php echo e($totalLawyers); ?></div>
            <a href="<?php echo e(route('admin.lawyers')); ?>" class="text-[11px] font-bold text-blue-600 hover:underline mt-2 block">Manage Lawyer Profiles &rarr;</a>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Customers</div>
            <div class="text-3xl font-extrabold text-slate-900"><?php echo e($totalCustomers); ?></div>
            <span class="text-[11px] text-slate-400 mt-2 block">Registered Clients</span>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Legal Services</div>
            <div class="text-3xl font-extrabold text-emerald-600"><?php echo e($totalServices); ?></div>
            <a href="<?php echo e(route('admin.services')); ?>" class="text-[11px] font-bold text-emerald-600 hover:underline mt-2 block">Manage Services &rarr;</a>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Appointments</div>
            <div class="text-3xl font-extrabold text-purple-600"><?php echo e($totalAppointments); ?></div>
            <a href="<?php echo e(route('admin.appointments')); ?>" class="text-[11px] font-bold text-purple-600 hover:underline mt-2 block">Manage Slots &rarr;</a>
        </div>
    </div>

    <!-- Pending Lawyer Approvals Warning Banner -->
    <?php if(count($pendingLawyers) > 0): ?>
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-extrabold text-amber-900 text-sm flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                Lawyers Pending Approval (<?php echo e(count($pendingLawyers)); ?>)
            </h3>
            <a href="<?php echo e(route('admin.lawyers')); ?>" class="text-xs font-bold text-amber-800 hover:underline">Review All</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <?php $__currentLoopData = $pendingLawyers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lawyer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white p-3 rounded-lg border border-amber-200 text-xs flex justify-between items-center">
                <div>
                    <div class="font-bold text-slate-900"><?php echo e($lawyer->name); ?></div>
                    <div class="text-[10px] text-slate-500"><?php echo e($lawyer->email); ?></div>
                </div>
                <form action="<?php echo e(route('admin.lawyers.approval', $lawyer->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-2.5 py-1 rounded text-[11px] transition">
                        Approve Profile
                    </button>
                </form>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Recent System Appointments -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-150 flex items-center justify-between">
            <h2 class="font-extrabold text-base text-slate-900">Recent System-wide Appointments</h2>
            <a href="<?php echo e(route('admin.appointments')); ?>" class="text-xs font-bold text-blue-600 hover:underline">View All Appointments</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Appt Number</th>
                        <th class="px-6 py-3.5">Customer</th>
                        <th class="px-6 py-3.5">Assigned Lawyer</th>
                        <th class="px-6 py-3.5">Service</th>
                        <th class="px-6 py-3.5">Date & Slot</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-150 font-medium">
                    <?php $__currentLoopData = $recentAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $apt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-mono font-bold text-blue-600"><?php echo e($apt->appointment_number); ?></td>
                        <td class="px-6 py-4 font-bold text-slate-900"><?php echo e($apt->customer->name ?? 'Customer'); ?></td>
                        <td class="px-6 py-4 font-bold text-slate-900"><?php echo e($apt->lawyer->name ?? 'Lawyer'); ?></td>
                        <td class="px-6 py-4"><?php echo e($apt->service->name ?? 'N/A'); ?></td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900"><?php echo e(date('M j, Y', strtotime($apt->appointment_date))); ?></div>
                            <div class="text-[11px] text-slate-500"><?php echo e(date('g:i A', strtotime($apt->start_time))); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-slate-100 text-slate-800 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-300">
                                <?php echo e(ucfirst($apt->status)); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\A.r Computer's\OneDrive\Videos\eProject Lawyers Website\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>