<?php $__env->startSection('content'); ?>
<div class="max-w-md mx-auto my-16 px-4">
    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-8">
        
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-xl bg-navy-900 text-amber-400 flex items-center justify-center mx-auto mb-3 text-xl shadow-sm">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h1 class="text-2xl font-bold text-navy-900 tracking-tight">Forgot Your Password?</h1>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                Enter your registered email address and we'll send you a secure password reset link to regain access to your account.
            </p>
        </div>

        <?php if(session('status')): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-lg p-3.5 mb-6 flex items-start gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-sm mt-0.5"></i>
            <div>
                <span class="font-semibold block mb-0.5">Reset Link Sent</span>
                <?php echo e(session('status')); ?>

            </div>
        </div>
        <?php endif; ?>

        <?php if(isset($errors) && $errors->any()): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 text-xs rounded-lg p-3.5 mb-6 flex items-start gap-2">
            <i class="fa-solid fa-circle-exclamation text-red-600 text-sm mt-0.5"></i>
            <ul class="list-disc list-inside space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form action="<?php echo e(route('password.email')); ?>" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition"
                        placeholder="e.g. attorney@juris.com">
                </div>
            </div>

            <button type="submit" class="w-full bg-navy-900 hover:bg-blue-600 text-white font-bold py-3 rounded-lg text-sm transition shadow-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-paper-plane text-xs text-amber-400"></i> Send Password Reset Link
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
            <a href="<?php echo e(route('login')); ?>" class="text-blue-600 font-semibold hover:underline inline-flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-xs"></i> Back to Login
            </a>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\A.r Computer's\OneDrive\Videos\eProject Lawyers Website\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>