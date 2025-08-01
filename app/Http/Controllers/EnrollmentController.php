<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $user = auth()->user();

        // Check if user has sufficient balance
        if($user->course_balance <= 0) {
            // Redirect to pricing page if no balance
            return redirect()->route('pricing.page')
                ->with('error', 'Your course balance is zero. Please choose a plan to enroll in courses.');
        }

        // Check if user is already enrolled
        $existingEnrollment = $user->enrollments()->where('course_id', $course->id)->first();
        if ($existingEnrollment) {
            return redirect()->back()
                ->with('error', 'You are already enrolled in this course.');
        }

        // Create enrollment
        $user->enrollments()->create(['course_id' => $course->id]);

        // Return success response for AJAX or redirect for normal requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "You have been enrolled in \"{$course->title}\"",
                'remaining_balance' => $user->fresh()->course_balance
            ]);
        }

        return redirect()->back()
            ->with('success', "You have been enrolled in \"{$course->title}\". Remaining balance: {$user->fresh()->course_balance}");
    }
public function enroll(Request $request, \App\Models\Course $course)
{
    $user = auth()->user();

    // Check balance
    if ($user->course_balance <= 0) {
        return response()->json([
            'error' => 'No course balance. Please subscribe to a plan.',
            'redirect' => route('pricing.page')
        ], 403);
    }

    // Prevent duplicate enrollments
    if ($user->enrollments()->where('course_id', $course->id)->exists()) {
        return response()->json([
            'error' => 'You are already enrolled in this course.'
        ], 409);
    }

    // Proceed to enroll
    $user->enrollments()->create([
        'course_id' => $course->id,
    ]);

    return response()->json([
        'message' => "You have been enrolled in {$course->title}.",
        'balance' => $user->course_balance
    ]);
}


}
