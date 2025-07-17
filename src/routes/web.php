<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminAttendanceController;

// トップページ
Route::get('/', function () {
    return view('welcome');
});

// 認証関連
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [RegisterController::class, 'showLoginForm'])->name('login');
Route::post('/login', [RegisterController::class, 'login']);
Route::post('/logout', [RegisterController::class, 'logout'])->name('logout');

// 管理者ログイン画面
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

// メール認証
Route::get('/email/verify', function () {
    return view('verify-email');
})->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('attendance.list');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました。');
})->middleware('throttle:6,1')->name('verification.send');

// ===== 一般ユーザー =====
Route::get('/attendance/create', function () {
    return view('attendance.create');
})->name('attendance.create');

Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');
Route::get('/attendance/detail/{attendance}', [AttendanceController::class, 'detail'])->name('attendance.detail');
Route::get('/attendance/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
Route::post('/attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');

Route::get('/application', [AttendanceController::class, 'application'])->name('application');

// ===== 管理者専用 =====
Route::prefix('admin')->name('admin.')->group(function () {
    // 勤怠管理
    Route::get('/attendances', [AdminAttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/{attendance}', [AdminAttendanceController::class, 'show'])->name('attendances.show');
    Route::put('/attendances/{attendance}', [AdminAttendanceController::class, 'update'])->name('attendances.update');

    // スタッフ管理
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/{id}/attendance', [StaffController::class, 'attendanceDetail'])->name('staff.attendance');
    Route::get('/staff/{id}/attendance/csv', [StaffController::class, 'attendanceCsv'])->name('staff.attendance.csv');

    // 申請管理
    Route::get('/applications', [ApplicationController::class, 'index'])->name('application.index');
    Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('application.show');
    Route::put('/applications/{id}/approve', [ApplicationController::class, 'approve'])->name('application.approve');
});
