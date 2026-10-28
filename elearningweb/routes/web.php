<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\SalesController as AdminSalesController;
use App\Http\Controllers\Admin\TeacherApprovalController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\LessonWatchController;
use App\Http\Controllers\TeacherPendingController;
use App\Http\Controllers\Teacher\CourseController as TeacherCourseController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\LessonController as TeacherLessonController;
use App\Http\Controllers\Teacher\ProgressController as TeacherProgressController;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureStudent;
use App\Http\Middleware\EnsureTeacher;
use App\Http\Middleware\EnsureTeacherApproved;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', [CourseController::class, 'index'])->name('home');
Route::get('about', fn () => Inertia::render('Public/About'))->name('about');
Route::get('support', fn () => Inertia::render('Public/Support'))->name('support');
Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware([EnsureStudent::class])->group(function () {
        Route::post('courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
        Route::get('checkout/{enrollment}', [CheckoutController::class, 'show'])->name('checkout.show');
        Route::post('checkout/{enrollment}', [CheckoutController::class, 'pay'])->name('checkout.pay');
        Route::get('courses/{course}/lessons/{lesson}', [LessonWatchController::class, 'show'])->name('courses.lessons.watch');
        Route::post('courses/{course}/lessons/{lesson}/complete', [LessonWatchController::class, 'complete'])->name('courses.lessons.complete');
        Route::get('courses/{course}/lessons/{lesson}/assessment', [AssessmentController::class, 'show'])->name('courses.lessons.assessment.show');
        Route::post('courses/{course}/lessons/{lesson}/assessment', [AssessmentController::class, 'submit'])->name('courses.lessons.assessment.submit');
    });

    Route::middleware([EnsureTeacher::class])
        ->prefix('teacher')
        ->name('teacher.')
        ->group(function () {
            Route::get('pending', [TeacherPendingController::class, 'index'])->name('pending');
        });

    Route::middleware([EnsureTeacher::class, EnsureTeacherApproved::class])
        ->prefix('teacher')
        ->name('teacher.')
        ->group(function () {
            Route::get('dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
            Route::get('courses', [TeacherCourseController::class, 'index'])->name('courses.index');
            Route::get('courses/create', [TeacherCourseController::class, 'create'])->name('courses.create');
            Route::post('courses', [TeacherCourseController::class, 'store'])->name('courses.store');
            Route::get('courses/{course}/edit', [TeacherCourseController::class, 'edit'])->name('courses.edit');
            Route::put('courses/{course}', [TeacherCourseController::class, 'update'])->name('courses.update');
            Route::delete('courses/{course}', [TeacherCourseController::class, 'destroy'])->name('courses.destroy');

            Route::get('courses/{course}/lessons', [TeacherLessonController::class, 'index'])->name('courses.lessons.index');
            Route::post('courses/{course}/lessons', [TeacherLessonController::class, 'store'])->name('courses.lessons.store');
            Route::put('courses/{course}/lessons/{lesson}', [TeacherLessonController::class, 'update'])->name('courses.lessons.update');
            Route::delete('courses/{course}/lessons/{lesson}', [TeacherLessonController::class, 'destroy'])->name('courses.lessons.destroy');
            Route::post('courses/{course}/lessons/reorder', [TeacherLessonController::class, 'reorder'])->name('courses.lessons.reorder');
            Route::get('courses/{course}/lessons/{lesson}/submissions', [TeacherLessonController::class, 'submissions'])->name('courses.lessons.submissions');
            Route::put('courses/{course}/lessons/{lesson}/submissions/{submission}', [TeacherLessonController::class, 'updateSubmission'])->name('courses.lessons.submissions.update');

            Route::get('courses/{course}/progress', [TeacherProgressController::class, 'show'])->name('courses.progress');
        });

    Route::middleware([EnsureAdmin::class])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('teachers/pending', [TeacherApprovalController::class, 'index'])->name('teachers.pending');
            Route::put('teachers/{user}/approve', [TeacherApprovalController::class, 'approve'])->name('teachers.approve');
            Route::put('teachers/{user}/reject', [TeacherApprovalController::class, 'reject'])->name('teachers.reject');

            Route::get('courses', [AdminCourseController::class, 'index'])->name('courses.index');
            Route::delete('courses/{course}', [AdminCourseController::class, 'destroy'])->name('courses.destroy');

            Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
            Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
            Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

            Route::get('sales', [AdminSalesController::class, 'index'])->name('sales.index');
            Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
            Route::put('payments/{payment}/approve', [AdminPaymentController::class, 'approve'])->name('payments.approve');
            Route::put('payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');
        });
});

require __DIR__.'/settings.php';

if (app()->environment('local')) {
    Route::get('_dev/phpinfo', function () {
        return response()->json([
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'memory_limit' => ini_get('memory_limit'),
            'max_input_time' => ini_get('max_input_time'),
            'max_execution_time' => ini_get('max_execution_time'),
            'php_ini_loaded_file' => php_ini_loaded_file(),
            'php_ini_scanned_files' => php_ini_scanned_files(),
        ]);
    });
}
