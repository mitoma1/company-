<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ApplicationController;

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

// ユーザーの勤怠関連
Route::get('/attendance/create', function () {
    return view('attendance.create');
})->name('attendance.create');

Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');

Route::get('/attendance/detail/{attendance}', [AttendanceController::class, 'detail'])->name('attendance.detail');

Route::get('/attendance/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
Route::post('/attendance/{attendance}/edit', [AttendanceController::class, 'update'])->name('attendance.update');

// ユーザー申請
Route::get('/application', [AttendanceController::class, 'application'])->name('application');

// ===== 管理者関連 =====
Route::prefix('admin')->group(function () {

    // 管理者勤怠一覧（既存のlistメソッドに変更）
    Route::get('/attendances', [AttendanceController::class, 'list'])->name('admin.attendances.index');

    // 管理者勤怠詳細
    Route::get('/attendances/{attendance}', [AttendanceController::class, 'adminDetail'])->name('admin.attendances.show');

    // 管理者勤怠更新（PUTなどがあれば）
    Route::put('/attendances/{attendance}', [AttendanceController::class, 'update'])->name('admin.attendances.update');

    // スタッフ一覧
    Route::get('/staff', [StaffController::class, 'index'])->name('admin.staff.index');

    // 管理者申請一覧（承認待ち・承認済み表示）
    Route::get('/applications', [ApplicationController::class, 'index'])->name('admin.application.index');

    // 修正申請詳細表示
    Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('admin.application.show');

    // 修正申請承認処理
    Route::put('/applications/{id}/approve', [ApplicationController::class, 'approve'])->name('admin.application.approve');
});

// スタッフ別の月次勤怠詳細
Route::get('/admin/staff/{id}/attendance', [StaffController::class, 'attendanceDetail'])
    ->name('admin.staff.attendance');

// スタッフ別の月次勤怠CSV出力
Route::get('/admin/staff/{id}/attendance/csv', [StaffController::class, 'attendanceCsv'])
    ->name('admin.staff.attendance.csv');
