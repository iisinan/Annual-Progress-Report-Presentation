<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\FileDownloadController;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Route;
use App\Models\SystemSetting;

Route::get('/', function () {
    $settings = SystemSetting::all()->pluck('value', 'key');
    return view('welcome', compact('settings'));
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Shared Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/notifications/read', [DashboardController::class, 'markNotificationsAsRead'])->name('notifications.read');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Student Routes
    Route::middleware(['role:Student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/upload-presentation', [StudentController::class, 'showUploadForm'])->name('upload')->middleware('check.upload');
        Route::post('/upload-presentation', [StudentController::class, 'uploadPresentation'])->name('upload.store')->middleware('check.upload');
        Route::get('/download-slip', [StudentController::class, 'downloadSlip'])->name('slip');
    });

    // Admin Routes
    Route::middleware(['role:Administrator'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/students', [AdminController::class, 'students'])->name('students');
        Route::post('/students/store', [AdminController::class, 'storeStudent'])->name('students.store');
        Route::delete('/students/{student}', [AdminController::class, 'destroyStudent'])->name('students.destroy');
        Route::get('/examiners', [AdminController::class, 'examiners'])->name('examiners');
        Route::post('/examiners/store', [AdminController::class, 'storeExaminer'])->name('examiners.store');
        Route::delete('/examiners/{examiner}', [AdminController::class, 'destroyExaminer'])->name('examiners.destroy');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
        
        // Scheduling
        Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
        Route::post('/schedule/generate', [ScheduleController::class, 'generate'])->name('schedule.generate');
        Route::post('/schedule/clear', [ScheduleController::class, 'clear'])->name('schedule.clear');
        Route::put('/schedule/{schedule}', [ScheduleController::class, 'update'])->name('schedule.update');
        Route::delete('/schedule/{schedule}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
        
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/students/excel', [ReportController::class, 'exportStudentsExcel'])->name('reports.students.excel');
        Route::get('/reports/students/pdf', [ReportController::class, 'exportStudentsPdf'])->name('reports.students.pdf');
        Route::get('/reports/grades/excel', [ReportController::class, 'exportGradesExcel'])->name('reports.grades.excel');
        
        // Announcements
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::post('/announcements/{announcement}/toggle', [AnnouncementController::class, 'toggleActive'])->name('announcements.toggle');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // Audit Trail
        Route::get('/audit-logs', [\App\Http\Controllers\AdminController::class, 'auditLogs'])->name('audit-logs');
    });

    // Shared Examiner & Admin Routes
    Route::middleware(['role:Examiner|Administrator'])->group(function () {
        Route::get('/admin/students/{student}', [AdminController::class, 'showStudent'])->name('admin.students.show');
        Route::post('/examiner/reviews/{student}', [\App\Http\Controllers\ReviewController::class, 'store'])->name('examiner.reviews.store');
        Route::put('/admin/schedule/{schedule}', [ScheduleController::class, 'update'])->name('admin.schedule.update');
        Route::get('/admin/reports/schedule/pdf', [ReportController::class, 'exportSchedulePdf'])->name('admin.reports.schedule.pdf');
    });
    Route::middleware(['role:Examiner|Administrator'])->group(function () {
        Route::get('/presentations/{presentation}/download', [FileDownloadController::class, 'downloadPresentation'])->name('presentations.download');
    });
    Route::middleware(['role:Examiner'])->prefix('examiner')->name('examiner.')->group(function () {
        Route::get('/students', [AdminController::class, 'students'])->name('students'); // Shared view or separate
        Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
    });
});

require __DIR__.'/auth.php';
