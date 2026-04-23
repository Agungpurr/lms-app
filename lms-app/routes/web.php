<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Instructor\InstructorDashboardController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\LessonController as InstructorLessonController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\EnrollController;
use App\Http\Controllers\Student\ProgressController;
use App\Http\Controllers\Student\QuizController;
use App\Http\Controllers\Student\CertificateController;
use App\Http\Controllers\CoursePublicController;
use Illuminate\Support\Facades\Route;

// ═══════════════════════════════════════════════════════════
// PUBLIC ROUTES
// ═══════════════════════════════════════════════════════════

Route::get('/', fn () => view('welcome'))->name('home');

Route::controller(CoursePublicController::class)->group(function () {
    Route::get('/courses',         'index')->name('courses.index');
    Route::get('/courses/{course}','show')->name('courses.show');
});

// ═══════════════════════════════════════════════════════════
// ADMIN ROUTES
// ═══════════════════════════════════════════════════════════

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    // User Management
    Route::resource('users', AdminUserController::class)
        ->except(['create', 'store']); // Admin tidak buat user manual

    // Course Management
    Route::resource('courses', AdminCourseController::class)
        ->except(['create', 'store']); // Instructor yang buat course
});

// ═══════════════════════════════════════════════════════════
// INSTRUCTOR ROUTES
// ═══════════════════════════════════════════════════════════

Route::middleware(['auth', 'verified', 'role:instructor'])
    ->prefix('instructor')
    ->name('instructor.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [InstructorDashboardController::class, 'index'])
        ->name('dashboard');

    // Course CRUD
    Route::resource('courses', InstructorCourseController::class);

    // Module (nested di bawah course)
    Route::prefix('courses/{course}')->name('courses.')->group(function () {

        // Modul
        Route::get   ('/modules/create',      [InstructorLessonController::class, 'createModule']) ->name('modules.create');
        Route::post  ('/modules',              [InstructorLessonController::class, 'storeModule'])  ->name('modules.store');
        Route::delete('/modules/{module}',     [InstructorLessonController::class, 'destroyModule'])->name('modules.destroy');

        // Lesson (nested di bawah module)
        Route::get   ('/modules/{module}/lessons/create',       [InstructorLessonController::class, 'create'])  ->name('modules.lessons.create');
        Route::post  ('/modules/{module}/lessons',              [InstructorLessonController::class, 'store'])   ->name('modules.lessons.store');
        Route::get   ('/modules/{module}/lessons/{lesson}/edit',[InstructorLessonController::class, 'edit'])    ->name('modules.lessons.edit');
        Route::put   ('/modules/{module}/lessons/{lesson}',     [InstructorLessonController::class, 'update'])  ->name('modules.lessons.update');
        Route::delete('/modules/{module}/lessons/{lesson}',     [InstructorLessonController::class, 'destroy']) ->name('modules.lessons.destroy');

        // Reorder lessons (AJAX)
        Route::post('/modules/{module}/lessons/reorder', [InstructorLessonController::class, 'reorder'])
            ->name('modules.lessons.reorder');
    });
});

// ═══════════════════════════════════════════════════════════
// STUDENT ROUTES
// ═══════════════════════════════════════════════════════════

Route::middleware(['auth', 'verified', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])
        ->name('dashboard');

    // Course — Enroll & Belajar
    Route::post('/courses/{course}/enroll',          [EnrollController::class, 'enroll']) ->name('courses.enroll');
    Route::get ('/courses/{course}',                 [EnrollController::class, 'show'])   ->name('courses.show');
    Route::get ('/courses/{course}/lessons/{lesson}',[EnrollController::class, 'lesson']) ->name('courses.lessons.show');

    // Progress
    Route::post('/lessons/{lesson}/complete',   [ProgressController::class, 'markComplete'])  ->name('lessons.complete');
    Route::post('/lessons/{lesson}/incomplete', [ProgressController::class, 'markIncomplete'])->name('lessons.incomplete');

    // Quiz
    Route::get ('/quiz/{quiz}',        [QuizController::class, 'show'])  ->name('quiz.show');
    Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get ('/quiz/result/{result}',[QuizController::class, 'result'])->name('quiz.result');

    // Certificate
    Route::get('/certificate/{enrollment}', [CertificateController::class, 'download'])
        ->name('certificate.download');
});

// ═══════════════════════════════════════════════════════════
// AUTH ROUTES (Breeze)
// ═══════════════════════════════════════════════════════════

require __DIR__ . '/auth.php';