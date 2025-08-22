<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AdminCourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CourseAnnouncementController;
use App\Models\Course;


// 🔓 Public Pages

Route::get('/', function () {
    $courses = Course::all();
    return view('home', compact('courses'));
})->name('home');

// Redirect /home to /
Route::get('/home', function () {
    return redirect()->route('home');
});
Route::view('/faq', 'faq');
Route::view('/contact', 'contact');
Route::view('/about', 'about');
Route::view('/courses', 'courses')->name('courses');
Route::view('/pricing', 'pricing')->name('pricing.page');

// Course detail pages - protected by auth
Route::middleware('auth')->group(function () {
    Route::get('/course/{course}', [CourseController::class, 'show'])->name('course.show');
    Route::get('/course/{course}/demo', [CourseController::class, 'demo'])->name('courses.demo');
    Route::get('/course/{course}/download', [CourseController::class, 'download'])->name('courses.download');
});

// 🔒 Auth-Protected Routes
Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');



 Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
});

// 🔐 Admin Panel
Route::middleware(['web', 'auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('courses', AdminCourseController::class)->except('show');
        
        // AJAX routes for courses
        Route::get('/courses/list', [AdminCourseController::class, 'list'])->name('courses.list');
        
        // Announcements management
        Route::get('/announcements', [App\Http\Controllers\AdminAnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements', [App\Http\Controllers\AdminAnnouncementController::class, 'store'])->name('announcements.store');
        Route::put('/announcements/{announcement}', [App\Http\Controllers\AdminAnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{announcement}', [App\Http\Controllers\AdminAnnouncementController::class, 'delete'])->name('announcements.delete');
        Route::patch('/announcements/{announcement}/toggle-pin', [App\Http\Controllers\AdminAnnouncementController::class, 'togglePin'])->name('announcements.toggle-pin');
        
        // AJAX routes for announcements
        Route::get('/announcements/list', [App\Http\Controllers\AdminAnnouncementController::class, 'list'])->name('announcements.list');
    });

// 💬 Chat System Routes
Route::middleware('auth')->prefix('chat')->name('chat.')->group(function () {
    Route::get('/conversations', [ChatController::class, 'getConversations'])->name('conversations');
    Route::get('/conversations/{conversationId}/messages', [ChatController::class, 'getMessages'])->name('messages');
    Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('send');
    Route::post('/create-private-chat', [ChatController::class, 'createPrivateChat'])->name('create-private');
    Route::get('/course/{courseId}/mates', [ChatController::class, 'getCourseMates'])->name('course-mates');
});

// 📢 Course Announcements Routes
Route::middleware('auth')->prefix('announcements')->name('announcements.')->group(function () {
    Route::get('/course/{courseId}', [CourseAnnouncementController::class, 'getAnnouncements'])->name('course');
    
    // Admin-only routes
    Route::middleware('admin')->group(function () {
        Route::post('/create', [CourseAnnouncementController::class, 'createAnnouncement'])->name('create');
        Route::put('/{announcementId}', [CourseAnnouncementController::class, 'updateAnnouncement'])->name('update');
        Route::delete('/{announcementId}', [CourseAnnouncementController::class, 'deleteAnnouncement'])->name('delete');
        Route::get('/all', [CourseAnnouncementController::class, 'getAllAnnouncements'])->name('all');
        Route::patch('/{announcementId}/toggle-pin', [CourseAnnouncementController::class, 'togglePin'])->name('toggle-pin');
    });
});
    Route::post(
  '/course/{course}/enroll',
  [EnrollmentController::class, 'enroll']
)->name('course.enroll')
 ->middleware('auth');
Route::post('/course/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('course.enroll');
Route::post('/course/{course}/drop', [EnrollmentController::class, 'dropCourse'])->name('course.drop')->middleware('auth');
// Course selector page
Route::view('/course-selector', 'course-selector')->name('course.selector');

// Suggest a course placeholder (optional)
Route::get('/suggest-course', [CourseController::class, 'showSuggestionFlow'])->name('suggest.course');
Route::post('/suggest-course/result', [CourseController::class, 'getSuggestedCourses'])->name('suggest.course.result');


Route::get('/contact', [ComplaintController::class, 'showForm'])->name('contact.form');

Route::middleware('auth')->post('/contact', [ComplaintController::class, 'submit'])->name('contact.submit');

require __DIR__.'/auth.php';
