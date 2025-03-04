<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiklatController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RekurenController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('auth', [LoginController::class, 'login'])->name('auth');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('mail', function () {
    return view('mail.mail');
});

Route::middleware(['auth'])->group(function () {

    Route::middleware('role:Pegawai')->group(function () {
        Route::get('/dasboard-pegawai', function () {
            return view('dashboard');
        })->name('dashboard-pegawai');
    });

    Route::middleware('role:Admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('employees', EmployeeController::class);
        Route::resource('licenses', LicenseController::class);
        Route::get('verification-license', [LicenseController::class, 'indexLicenses'])->name('verification-license');
        Route::get('rekuren-pengajuan', [RekurenController::class, 'index'])->name('rekuren.index');
        Route::post('licenses/accept', [RekurenController::class, 'acceptSubmission'])->name('rekuren.acceptSubmission');
        Route::post('licenses/reject', [RekurenController::class, 'rejectSubmission'])->name('rekuren.rejectSubmission');
        Route::get('schedule-rekuren', [RekurenController::class, 'indexSchedule'])->name('rekuren.indexSchedule');
        Route::put('schedule-rekuren/{schedule}/graduated', [RekurenController::class, 'graduated'])->name('rekuren.graduated');
        Route::get('rekuren-history', [RekurenController::class, 'indexRekurenHistory'])->name('rekuren.indexRekurenHistory');
        Route::get('diklat-pegawai', [DiklatController::class, 'index'])->name('diklat.index');
        Route::post('diklat-pegawai/store', [DiklatController::class, 'store'])->name('diklat.store');
        Route::get('diklat-schedule', [DiklatController::class, 'indexSchedule'])->name('diklat.indexSchedule');
        Route::put('diklat-schedule/{schedule}/graduated', [DiklatController::class, 'graduated'])->name('diklat.graduated');
        Route::get('diklat-history', [DiklatController::class, 'indexHistory'])->name('diklat.indexHistory');
        Route::put('license-action/{license}', [LicenseController::class, 'actionSubmission'])->name('licenses.submission');
    });

    Route::resource('pegawais', PegawaiController::class);
    Route::put('pegawais/{pegawai}/password', [PegawaiController::class, 'updatePassword'])->name('pegawais.updatePassword');
    Route::post('submission', [RekurenController::class, 'makeSubmission'])->name('rekuren.submission');
    Route::get('/certificate/download/{id}', [DiklatController::class, 'downloadFile'])->name('certificate.download');
    Route::post('license-submission', [PegawaiController::class, 'licenseSubmission'])->name('license-submission');
    Route::get('/notifications', [NotificationController::class, 'fetchNotifications'])->name('notifications');
    Route::get('/notifications/clear', [NotificationController::class, 'clearNotifications'])->name('notifications.clear');
});
