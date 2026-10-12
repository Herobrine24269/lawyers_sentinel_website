<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Manage Lawyer Profiles for Public Searches</h1>
            <p class="text-xs text-slate-500 mt-1">Approve profiles to allow them to be visible in customer searches, feature top advocates, or manage account access</p>
        </div>
        <span class="text-xs bg-slate-100 text-slate-700 font-bold px-3 py-1.5 rounded-lg border border-slate-200">
            Total Lawyers: <?php echo e(count($lawyers)); ?>

        </span>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Lawyer Info</th>
                        <th class="px-6 py-3.5">License & Exp</th>
                        <th class="px-6 py-3.5">Location</th>
                        <th class="px-6 py-3.5">Hourly Fee</th>
                        <th class="px-6 py-3.5">Search Approval</th>
                        <th class="px-6 py-3.5">Account Status</th>
                        <th class="px-6 py-3.5 text-right">Admin Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-150 font-medium">
                    <?php $__currentLoopData = $lawyers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lawyer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $profile = $lawyer->lawyerProfile; ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="relative group flex-shrink-0">
                                    <img src="<?php echo e($lawyer->profile_pic_url); ?>" alt="<?php echo e($lawyer->name); ?>"
                                        class="w-11 h-11 rounded-lg object-cover border border-slate-300 shadow-sm">
                                    <button onclick="document.getElementById('upload_pic_modal_<?php echo e($lawyer->id); ?>').classList.toggle('hidden')"
                                        title="Change Profile Picture"
                                        class="absolute -bottom-1 -right-1 bg-slate-900 text-white w-5 h-5 rounded-full text-[9px] flex items-center justify-center hover:bg-blue-600 transition shadow">
                                        <i class="fa-solid fa-camera"></i>
                                    </button>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                        <?php echo e($lawyer->name); ?>

                                        <?php if($profile && $profile->is_featured): ?>
                                            <span class="bg-amber-100 text-amber-800 text-[9px] font-bold px-1.5 py-0.5 rounded border border-amber-300">Featured</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-[11px] text-slate-500"><?php echo e($lawyer->email); ?> &bull; <?php echo e($lawyer->phone); ?></div>
                                </div>
                            </div>

                            <!-- Admin Profile Pic Upload Modal/Form -->
                            <div id="upload_pic_modal_<?php echo e($lawyer->id); ?>" class="hidden mt-2 p-3 bg-slate-100 rounded-lg border border-slate-300 space-y-2">
                                <span class="text-[11px] font-bold text-slate-800 block">Upload New Photo for <?php echo e($lawyer->name); ?>:</span>
                                <form action="<?php echo e(route('admin.lawyers.profile_pic', $lawyer->id)); ?>" method="POST" enctype="multipart/form-data" class="flex gap-2">
                                    <?php echo csrf_field(); ?>
                                    <input type="file" name="profile_pic" accept="image/*" required
                                        class="text-[10px] text-slate-600 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-blue-600 file:text-white border border-slate-300 rounded bg-white">
                                    <button type="submit" class="bg-emerald-600 text-white text-[10px] font-bold px-2.5 py-1 rounded hover:bg-emerald-700">
                                        Upload
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-mono text-slate-900 font-bold"><?php echo e($profile->license_number ?? 'N/A'); ?></div>
                            <div class="text-[11px] text-slate-500"><?php echo e($profile->experience_years ?? 0); ?> Yrs Exp</div>
                        </td>
                        <td class="px-6 py-4 text-slate-800"><?php echo e($profile->location ?? 'N/A'); ?></td>
                        <td class="px-6 py-4 font-bold text-blue-600">$<?php echo e(number_format($profile->hourly_fee ?? 0, 2)); ?>/hr</td>
                        <td class="px-6 py-4">
                            <?php if($profile && $profile->is_approved): ?>
                                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-300">
                                    <i class="fa-solid fa-eye mr-1"></i> Visible in Search
                                </span>
                            <?php else: ?>
                                <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded border border-amber-300">
                                    <i class="fa-solid fa-eye-slash mr-1"></i> Hidden (Pending)
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($lawyer->status === 'active'): ?>
                                <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-300">Active</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2 py-0.5 rounded border border-red-300">Suspended</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            
                            <!-- Approval Toggle Form -->
                            <form action="<?php echo e(route('admin.lawyers.approval', $lawyer->id)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-[11px] font-bold px-2.5 py-1 rounded border transition <?php echo e(($profile && $profile->is_approved) ? 'bg-slate-100 text-slate-700 border-slate-300 hover:bg-slate-200' : 'bg-emerald-600 text-white border-emerald-600 hover:bg-emerald-700'); ?>">
                                    <?php echo e(($profile && $profile->is_approved) ? 'Revoke Search' : 'Approve Profile'); ?>

                                </button>
                            </form>

                            <!-- Featured Toggle Form -->
                            <form action="<?php echo e(route('admin.lawyers.featured', $lawyer->id)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-[11px] font-bold px-2 py-1 rounded border border-slate-300 bg-white text-amber-700 hover:bg-amber-50">
                                    <i class="fa-solid fa-star"></i>
                                </button>
                            </form>

                            <!-- Account Status Toggle Form -->
                            <form action="<?php echo e(route('admin.lawyers.status', $lawyer->id)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-[11px] font-bold px-2.5 py-1 rounded border transition <?php echo e($lawyer->status === 'active' ? 'bg-red-50 text-red-700 border-red-200 hover:bg-red-100' : 'bg-blue-600 text-white border-blue-600'); ?>">
                                    <?php echo e($lawyer->status === 'active' ? 'Suspend' : 'Activate'); ?>

                                </button>
                            </form>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\A.r Computer's\OneDrive\Videos\eProject Lawyers Website\resources\views/admin/lawyers/index.blade.php ENDPATH**/ ?>