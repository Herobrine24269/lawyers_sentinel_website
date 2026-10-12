<?php $__env->startSection('content'); ?>
<div class="max-w-md mx-auto my-16 px-4">
    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-8">
        
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-xl bg-navy-900 text-amber-400 flex items-center justify-center mx-auto mb-3 text-xl">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h1 class="text-2xl font-bold text-navy-900 tracking-tight">Sign In to Your Account</h1>
            <p class="text-xs text-slate-500 mt-1">Access your Customer, Lawyer, or Admin portal</p>
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

        <form action="<?php echo e(route('login.post')); ?>" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition"
                        placeholder="e.g. john@example.com">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm">
                        <i class="fa-solid fa-key"></i>
                    </span>
                    <input type="password" name="password" required
                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center text-slate-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 mr-2">
                    Remember me
                </label>
                <a href="<?php echo e(route('password.request')); ?>" class="text-blue-600 hover:text-blue-800 font-semibold transition">
                    Forgot Password?
                </a>
            </div>

            <button type="submit" class="w-full bg-navy-900 hover:bg-blue-600 text-white font-bold py-3 rounded-lg text-sm transition shadow-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-right-to-bracket text-xs"></i> Log In
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
            Don't have an account yet?
            <div class="flex justify-center gap-4 mt-2">
                <a href="<?php echo e(route('register.customer')); ?>" class="text-blue-600 font-semibold hover:underline">Register as Customer</a>
                <span class="text-slate-300">|</span>
                <a href="<?php echo e(route('register.lawyer')); ?>" class="text-amber-600 font-semibold hover:underline">Register as Lawyer</a>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Desktop\eProject Lawyers Website\resources\views/auth/login.blade.php ENDPATH**/ ?>