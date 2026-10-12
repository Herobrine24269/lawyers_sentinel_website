<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto my-12 px-4">
    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-8">
        
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mx-auto mb-3 text-xl">
                <i class="fa-solid fa-gavel"></i>
            </div>
            <h1 class="text-2xl font-bold text-navy-900 tracking-tight">Lawyer Registration</h1>
            <p class="text-xs text-slate-500 mt-1">Join Juris Sentinel network to showcase your legal profile & offer booking slots</p>
        </div>

        <?php if($errors->any()): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 text-xs rounded-lg p-3 mb-6">
            <ul class="list-disc list-inside space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form action="<?php echo e(route('register.lawyer.post')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Full Name & Title</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 transition"
                        placeholder="Attorney Sarah Jenkins">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 transition"
                        placeholder="sarah@lawfirm.com">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 transition"
                        placeholder="+1 (555) 234-5678">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Location / Office City</label>
                    <input type="text" name="location" value="<?php echo e(old('location')); ?>" required
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 transition"
                        placeholder="New York, NY">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Profile Picture / Headshot <span class="text-[11px] font-normal text-slate-500">(Optional / اختیاری)</span>
                </label>
                <input type="file" name="profile_pic" accept="image/*"
                    class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100 border border-slate-300 rounded-lg cursor-pointer">
                <p class="text-[10px] text-slate-400 mt-1">Professional photograph (PNG, JPG, WEBP up to 4MB)</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Bar License Number</label>
                    <input type="text" name="license_number" value="<?php echo e(old('license_number')); ?>" required
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 transition"
                        placeholder="NY-BAR-88912">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Years of Experience</label>
                    <input type="number" name="experience_years" value="<?php echo e(old('experience_years', 5)); ?>" required min="0"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Hourly Consultation Fee ($)</label>
                    <input type="number" step="0.01" name="hourly_fee" value="<?php echo e(old('hourly_fee', 200)); ?>" required min="0"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Meeting Options / Formats</label>
                <input type="text" name="meeting_place" value="<?php echo e(old('meeting_place', 'In-Person Office, Virtual Video Call')); ?>" required
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 transition"
                    placeholder="e.g. In-Person Office, Virtual Call">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">Legal Specializations (Select all that apply)</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex items-center text-xs font-medium text-slate-700 cursor-pointer">
                        <input type="checkbox" name="specializations[]" value="<?php echo e($svc->id); ?>" class="rounded border-slate-300 text-amber-600 focus:ring-amber-500 mr-2">
                        <?php echo e($svc->name); ?>

                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Lawyer Biography & Credentials</label>
                <textarea name="bio" rows="3" required
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 transition"
                    placeholder="Describe your legal practice, key litigation wins, and background..."></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 transition"
                        placeholder="••••••••">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 transition"
                        placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="w-full bg-navy-900 hover:bg-amber-600 text-white font-bold py-3 rounded-lg text-sm transition shadow-sm flex items-center justify-center gap-2 mt-2">
                <i class="fa-solid fa-file-signature text-xs"></i> Register Lawyer Profile
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-500">
            Already registered? <a href="<?php echo e(route('login')); ?>" class="text-blue-600 font-semibold hover:underline">Sign in to Lawyer Console</a>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\A.r Computer's\OneDrive\Videos\eProject Lawyers Website\resources\views/auth/register-lawyer.blade.php ENDPATH**/ ?>