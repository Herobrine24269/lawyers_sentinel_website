<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LawyerSearchController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\LawyerDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLawyerController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminPaymentSettingController;
use App\Http\Controllers\Admin\AdminHomepageController;
use App\Http\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes - Lawyer Appointment Portal
|--------------------------------------------------------------------------
*/

// Public / Guest Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lawyers', [LawyerSearchController::class, 'index'])->name('lawyers.index');
Route::get('/lawyers/{id}', [LawyerSearchController::class, 'show'])->name('lawyers.show');

// Storage File Serving Route (Guarantees profile picture access across all dev environments)
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    $mimeType = mime_content_type($filePath) ?: 'image/png';
    return response()->file($filePath, ['Content-Type' => $mimeType]);
})->where('path', '.*');

// Public API endpoints for Frontend Fetch
Route::get('/api/lawyers/search', [LawyerSearchController::class, 'searchApi'])->name('api.lawyers.search');
Route::get('/api/lawyers/{id}/slots', [LawyerSearchController::class, 'getSlotsApi'])->name('api.lawyers.slots');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register/customer', [AuthController::class, 'showCustomerRegister'])->name('register.customer');
Route::post('/register/customer', [AuthController::class, 'registerCustomer'])->name('register.customer.post');
Route::get('/register/lawyer', [AuthController::class, 'showLawyerRegister'])->name('register.lawyer');
Route::post('/register/lawyer', [AuthController::class, 'registerLawyer'])->name('register.lawyer.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Booking Action
Route::post('/appointments/book', [AppointmentController::class, 'book'])->name('appointments.book');

// Customer Protected Routes
Route::middleware(['auth', RoleMiddleware::class . ':customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::post('/customer/appointments/{id}/cancel', [CustomerDashboardController::class, 'cancelAppointment'])->name('customer.appointments.cancel');
    Route::post('/customer/profile', [CustomerDashboardController::class, 'updateProfile'])->name('customer.profile.update');

    // Customer-only payment routes
    Route::get('/customer/appointments/{appointment}/payment', [PaymentController::class, 'create'])
        ->name('customer.payment.create');
    Route::post('/customer/appointments/{appointment}/payment', [PaymentController::class, 'store'])
        ->name('customer.payment.store');
    Route::get('/customer/payments/{payment}', [PaymentController::class, 'show'])
        ->name('customer.payment.show');
});

// Shared authenticated payment document routes.
// These must NOT be inside the customer-role middleware because admin
// payment pages also need to view proofs and receipts.
Route::middleware('auth')->group(function () {
    Route::get('/payments/{payment}/proof', [PaymentController::class, 'proof'])
        ->name('customer.payment.proof');
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])
        ->name('customer.payment.receipt');
    Route::get('/payments/{payment}/receipt/download', [PaymentController::class, 'downloadReceipt'])
        ->name('customer.payment.receipt.download');
});

// Lawyer Protected Routes
Route::middleware(['auth', RoleMiddleware::class . ':lawyer'])->group(function () {
    Route::get('/lawyer/dashboard', [LawyerDashboardController::class, 'index'])->name('lawyer.dashboard');
    Route::get('/lawyer/profile', [LawyerDashboardController::class, 'editProfile'])->name('lawyer.profile');
    Route::post('/lawyer/profile', [LawyerDashboardController::class, 'updateProfile'])->name('lawyer.profile.update');
    Route::get('/lawyer/schedule', [LawyerDashboardController::class, 'schedule'])->name('lawyer.schedule');
    Route::post('/lawyer/schedule', [LawyerDashboardController::class, 'updateSchedule'])->name('lawyer.schedule.update');
    Route::get('/lawyer/appointments', [LawyerDashboardController::class, 'appointments'])->name('lawyer.appointments');
    Route::post('/lawyer/appointments/{id}/status', [LawyerDashboardController::class, 'updateAppointmentStatus'])->name('lawyer.appointments.status');
});

// Admin Protected Routes
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/lawyers', [AdminLawyerController::class, 'index'])->name('admin.lawyers');
    Route::post('/admin/lawyers/{id}/approval', [AdminLawyerController::class, 'toggleApproval'])->name('admin.lawyers.approval');
    Route::post('/admin/lawyers/{id}/status', [AdminLawyerController::class, 'toggleStatus'])->name('admin.lawyers.status');
    Route::post('/admin/lawyers/{id}/featured', [AdminLawyerController::class, 'toggleFeatured'])->name('admin.lawyers.featured');
    Route::post('/admin/lawyers/{id}/profile-pic', [AdminLawyerController::class, 'updateProfilePic'])->name('admin.lawyers.profile_pic');
    Route::post('/admin/profile', [AdminLawyerController::class, 'updateAdminProfile'])->name('admin.profile.update');
    Route::get('/admin/services', [AdminServiceController::class, 'index'])->name('admin.services');
    Route::post('/admin/services', [AdminServiceController::class, 'store'])->name('admin.services.store');
    Route::post('/admin/services/{id}/toggle', [AdminServiceController::class, 'toggleStatus'])->name('admin.services.toggle');
    Route::get('/admin/appointments', [AdminAppointmentController::class, 'index'])->name('admin.appointments');
    Route::post('/admin/appointments/{id}/status', [AdminAppointmentController::class, 'updateStatus'])->name('admin.appointments.status');

    // Admin Payment Routes
    Route::get('/admin/payments', [AdminPaymentController::class, 'index'])->name('admin.payments');
    Route::get('/admin/payments/{payment}', [AdminPaymentController::class, 'show'])->name('admin.payments.show');
    Route::post('/admin/payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('admin.payments.verify');
    Route::post('/admin/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('admin.payments.reject');
    Route::get('/admin/payment-settings', [AdminPaymentSettingController::class, 'index'])->name('admin.payment-settings');
    Route::post('/admin/payment-settings', [AdminPaymentSettingController::class, 'update'])->name('admin.payment-settings.update');

    Route::get('/admin/homepage', [AdminHomepageController::class, 'index'])->name('admin.homepage');
    Route::post('/admin/homepage', [AdminHomepageController::class, 'update'])->name('admin.homepage.update');
});
