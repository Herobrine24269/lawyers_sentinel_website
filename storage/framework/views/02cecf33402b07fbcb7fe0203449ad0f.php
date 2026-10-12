<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <h1 class="text-xl font-extrabold text-slate-900">Manage Lawyer Profile Details</h1>
        <p class="text-xs text-slate-500 mt-1">This information will be displayed to customers when searching for lawyers</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <form action="<?php echo e(route('lawyer.profile.update')); ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
            <?php echo csrf_field(); ?>

            <!-- Current Avatar Preview & File Input -->
            <div class="flex flex-col sm:flex-row items-center gap-5 p-4 bg-slate-50 rounded-xl border border-slate-200 mb-2">
                <img src="<?php echo e($lawyer->profile_pic_url); ?>" alt="<?php echo e($lawyer->name); ?>"
                    class="w-20 h-20 rounded-xl object-cover border-2 border-amber-400 shadow-sm flex-shrink-0">
                <div class="space-y-1 flex-1 text-center sm:text-left">
                    <label class="block text-xs font-bold text-slate-900">
                        Profile Picture / Avatar <span class="text-[11px] font-normal text-slate-500">(Optional / اختیاری)</span>
                    </label>
                    <p class="text-[11px] text-slate-500">Upload a high-resolution photo. Leave empty to keep your current picture.</p>
                    <input type="file" name="profile_pic" accept="image/*"
                        class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-navy-900 file:text-white hover:file:bg-blue-600 border border-slate-300 rounded-lg cursor-pointer mt-1">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Full Name & Title</label>
                    <input type="text" name="name" value="<?php echo e(old('name', $lawyer->name)); ?>" required
                        class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Contact Phone</label>
                    <input type="text" name="phone" value="<?php echo e(old('phone', $lawyer->phone)); ?>" required
                        class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Location / City</label>
                    <input type="text" name="location" value="<?php echo e(old('location', $profile->location)); ?>" required
                        class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Meeting Options / Format</label>
                    <input type="text" name="meeting_place" value="<?php echo e(old('meeting_place', $profile->meeting_place)); ?>" required
                        class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Bar License Number</label>
                    <input type="text" name="license_number" value="<?php echo e(old('license_number', $profile->license_number)); ?>" required
                        class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Years of Experience</label>
                    <input type="number" name="experience_years" value="<?php echo e(old('experience_years', $profile->experience_years)); ?>" required min="0"
                        class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Hourly Fee ($)</label>
                    <input type="number" step="0.01" name="hourly_fee" value="<?php echo e(old('hourly_fee', $profile->hourly_fee)); ?>" required min="0"
                        class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">Legal Specializations Offered</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex items-center text-xs font-medium text-slate-700 cursor-pointer">
                        <input type="checkbox" name="specializations[]" value="<?php echo e($svc->id); ?>"
                            <?php echo e(in_array($svc->id, $profile->specializations ?? []) ? 'checked' : ''); ?>

                            class="rounded border-slate-300 text-blue-600 mr-2">
                        <?php echo e($svc->name); ?>

                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Lawyer Biography</label>
                <textarea name="bio" rows="4" required
                    class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600"><?php echo e(old('bio', $profile->bio)); ?></textarea>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-slate-900 text-white font-bold px-6 py-2.5 rounded-lg text-xs transition">
                Save Profile Changes
            </button>
        </form>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lawyer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\A.r Computer's\OneDrive\Videos\eProject Lawyers Website\resources\views/lawyer/profile.blade.php ENDPATH**/ ?>