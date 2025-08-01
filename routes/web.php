<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AdminCourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\SubscriptionController;
use App\Models\Course;


// 🔓 Public Pages
Route::get('/', function () {
    $courses = Course::all();
    return view('home', compact('courses'));
})->name('home');
Route::get('/home', function () {
    $courses = Course::all();
    return view('home', compact('courses'));
})->name('home');

Route::view('/faq', 'faq');
Route::view('/contact', 'contact');
Route::view('/about', 'about');
Route::view('/courses', 'courses')->name('courses');
Route::view('/pricing', 'pricing')->name('pricing.page');

// Course detail pages
Route::get('/course/{course}', [CourseController::class, 'show'])->name('course.show');
Route::get('/course/{course}/demo', [CourseController::class, 'demo'])->name('courses.demo');
Route::get('/course/{course}/download', [CourseController::class, 'download'])->name('courses.download');

// 🔒 Auth-Protected Routes
Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


 Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
});

// 🔐 Admin Panel
Route::middleware(['web', 'auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('courses', AdminCourseController::class)->except('show');
    });
    Route::post(
  '/course/{course}/enroll',
  [EnrollmentController::class, 'enroll']
)->name('course.enroll')
 ->middleware('auth');
Route::post('/course/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('course.enroll');

require __DIR__.'/auth.php';
