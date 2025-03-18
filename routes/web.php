<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\EnrollmentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Student routes
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])
        ->name('student.dashboard');

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/students', [StudentController::class, 'index'])->name('admin.students');
        Route::get('/students/create', [StudentController::class, 'create'])->name('admin.students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('admin.students.store');
        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');
        Route::put('/students/{student}', [StudentController::class, 'update'])->name('admin.students.update');
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('admin.students.destroy');
        Route::get('/subjects', [SubjectController::class, 'index'])->name('admin.subjects');
        Route::post('/subjects', [SubjectController::class, 'store'])->name('admin.subjects.store');
        Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('admin.subjects.update');
        Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('admin.subjects.destroy');
        Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('admin.enrollments');
        Route::post('/enrollments', [EnrollmentController::class, 'store'])->name('admin.enrollments.store');
        Route::delete('/enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->name('admin.enrollments.destroy');
        Route::put('/enrollments/{enrollment}', [EnrollmentController::class, 'update'])->name('admin.enrollments.update');
        Route::get('/grades', [AdminController::class, 'grades'])->name('admin.grades');
    });
});

require __DIR__.'/auth.php';
