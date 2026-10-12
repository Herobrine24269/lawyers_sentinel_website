<?php $__env->startSection('content'); ?>
<div class="max-w-lg mx-auto my-14 px-4">
    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-8">
        
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mx-auto mb-3 text-xl">
                <i class="fa-solid fa-user"></i>
            </div>
            <h1 class="text-2xl font-bold text-navy-900 tracking-tight">Customer Registration</h1>
            <p class="text-xs text-slate-500 mt-1">Create an account to search lawyers and book legal consultation slots</p>
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

        <form action="<?php echo e(route('register.customer.post')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?php echo csrf_field(); ?>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Full Name</label>
                <input type="text" name="name" value="<?php echo e(old('name')); ?>" required
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 transition"
                    placeholder="John Doe">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 transition"
                    placeholder="john@example.com">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Phone Number (Optional)</label>
                <input type="text" name="phone" value="<?php echo e(old('phone')); ?>"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 transition"
                    placeholder="+1 (555) 000-0000">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Profile Picture <span class="text-[11px] font-normal text-slate-500">(Optional / اختیاری)</span>
                </label>
                <input type="file" name="profile_pic" accept="image/*"
                    class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-300 rounded-lg cursor-pointer">
                <p class="text-[10px] text-slate-400 mt-1">PNG, JPG, WEBP up to 4MB</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 transition"
                        placeholder="••••••••">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 transition"
                        placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-navy-900 text-white font-bold py-3 rounded-lg text-sm transition shadow-sm flex items-center justify-center gap-2 mt-2">
                <i class="fa-solid fa-user-check text-xs"></i> Create Customer Account
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-500">
            Already have an account? <a href="<?php echo e(route('login')); ?>" class="text-blue-600 font-semibold hover:underline">Log in here</a>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\A.r Computer's\OneDrive\Videos\eProject Lawyers Website\resources\views/auth/register-customer.blade.php ENDPATH**/ ?>