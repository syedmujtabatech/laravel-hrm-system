<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\SolidGuideController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/quick-login/{type}', [AuthController::class, 'quickLogin'])->name('quick.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// SMS Account Activation Routes
Route::get('/activate', [AuthController::class, 'showActivation'])->name('activate.show');
Route::post('/activate', [AuthController::class, 'activate'])->name('activate.save');

// Authenticated & Active User Routes
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/punch', [AttendanceController::class, 'punch'])->name('attendance.punch');
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::post('/leaves/apply', [LeaveController::class, 'store'])->name('leaves.apply');
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/payroll/slip/{payroll}', [PayrollController::class, 'slip'])->name('payroll.slip');
    Route::redirect('/solid-guide', '/dashboard')->name('solid.guide');

    // Admin-Only Routes
    Route::middleware('admin')->group(function () {
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::post('/employees/{user}/offboard', [EmployeeController::class, 'offboard'])->name('employees.offboard');
        Route::post('/employees/{user}/resend-sms', [EmployeeController::class, 'resendSms'])->name('employees.resend-sms');
        Route::post('/leaves/{leave}/status', [LeaveController::class, 'updateStatus'])->name('leaves.status');
        Route::post('/payroll/disburse', [PayrollController::class, 'disburse'])->name('payroll.disburse');
    });
});
