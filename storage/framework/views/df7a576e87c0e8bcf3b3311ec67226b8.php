<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <h1 class="text-xl font-extrabold text-slate-900">Manage Homepage Banners & Content</h1>
        <p class="text-xs text-slate-500 mt-1">Customize headlines, top announcement bar, and platform metrics shown to public users</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <form action="<?php echo e(route('admin.homepage.update')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Top Announcement Banner Text</label>
                <input type="text" name="announcement_banner" value="<?php echo e(old('announcement_banner', $content->announcement_banner)); ?>" required
                    class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Hero Section Title</label>
                <input type="text" name="hero_title" value="<?php echo e(old('hero_title', $content->hero_title)); ?>" required
                    class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Hero Subtitle / Tagline</label>
                <textarea name="hero_subtitle" rows="3" required
                    class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600"><?php echo e(old('hero_subtitle', $content->hero_subtitle)); ?></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Total Lawyers Display Metric</label>
                    <input type="number" name="total_lawyers_count" value="<?php echo e(old('total_lawyers_count', $content->total_lawyers_count)); ?>" required
                        class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Total Consultations Metric</label>
                    <input type="number" name="total_cases_count" value="<?php echo e(old('total_cases_count', $content->total_cases_count)); ?>" required
                        class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Client Satisfaction %</label>
                    <input type="text" name="satisfaction_rate" value="<?php echo e(old('satisfaction_rate', $content->satisfaction_rate)); ?>" required
                        class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                </div>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-slate-900 text-white font-bold px-6 py-2.5 rounded-lg text-xs transition">
                Update Homepage Contents
            </button>
        </form>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Desktop\eProject Lawyers Website\resources\views/admin/homepage/index.blade.php ENDPATH**/ ?>