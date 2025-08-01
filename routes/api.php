<?php
// file: routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EnrollmentController;

// --- Courses API ---
Route::get('/courses',   [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::post('/courses',     [CourseController::class, 'store']);
Route::put('/courses/{id}', [CourseController::class, 'update']);
Route::delete('/courses/{id}',[CourseController::class,'destroy']);
// --- Other resources ---
Route::apiResource('categories',   CategoryController::class);
Route::apiResource('enrollments',  EnrollmentController::class);

// --- Authenticated user info (if you need it) ---
Route::middleware('auth:sanctum')->get('/user', function(Request $request){
  return $request->user();
});
