<?php

namespace App\Http\Controllers;

use App\Models\CourseAnnouncement;
use App\Models\Course;
use App\Models\User;
use App\Events\CourseAnnouncementCreated;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CourseAnnouncementController extends Controller
{
    /**
     * Get announcements for a specific course
     */
    public function getAnnouncements(int $courseId): JsonResponse
    {
        $user = Auth::user();
        
        // Check if user is enrolled in this course
        if (!$user->enrollments()->where('course_id', $courseId)->exists()) {
            return response()->json(['error' => 'Not enrolled in this course'], 403);
        }

        $announcements = CourseAnnouncement::where('course_id', $courseId)
            ->active()
            ->byPriority()
            ->with('admin')
            ->get()
            ->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'content' => $announcement->content,
                    'priority' => $announcement->priority,
                    'priority_color' => $announcement->getPriorityColor(),
                    'priority_icon' => $announcement->getPriorityIcon(),
                    'is_pinned' => $announcement->is_pinned,
                    'admin' => [
                        'id' => $announcement->admin->id,
                        'name' => $announcement->admin->name,
                    ],
                    'created_at' => $announcement->created_at->toISOString(),
                    'formatted_time' => $announcement->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'announcements' => $announcements
        ]);
    }

    /**
     * Create a new course announcement (admin only)
     */
    public function createAnnouncement(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Admin access required'], 403);
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:2000',
            'priority' => 'required|in:low,normal,high,urgent',
            'is_pinned' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $announcement = CourseAnnouncement::create([
            'course_id' => $request->course_id,
            'admin_id' => $user->id,
            'title' => $request->title,
            'content' => $request->content,
            'priority' => $request->priority,
            'is_pinned' => $request->is_pinned ?? false,
            'expires_at' => $request->expires_at,
        ]);

        // Broadcast announcement to course participants (we'll implement this next)
        event(new CourseAnnouncementCreated($announcement));

        return response()->json([
            'success' => true,
            'announcement' => [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'content' => $announcement->content,
                'priority' => $announcement->priority,
                'priority_color' => $announcement->getPriorityColor(),
                'priority_icon' => $announcement->getPriorityIcon(),
                'is_pinned' => $announcement->is_pinned,
                'admin' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ],
                'created_at' => $announcement->created_at->toISOString(),
                'formatted_time' => $announcement->created_at->diffForHumans(),
            ],
            'message' => 'Announcement created successfully'
        ]);
    }

    /**
     * Update an announcement (admin only)
     */
    public function updateAnnouncement(Request $request, int $announcementId): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Admin access required'], 403);
        }

        $announcement = CourseAnnouncement::findOrFail($announcementId);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string|max:2000',
            'priority' => 'sometimes|required|in:low,normal,high,urgent',
            'is_pinned' => 'sometimes|boolean',
            'expires_at' => 'sometimes|nullable|date|after:now',
        ]);

        $announcement->update($request->only([
            'title', 'content', 'priority', 'is_pinned', 'expires_at'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Announcement updated successfully'
        ]);
    }

    /**
     * Delete an announcement (admin only)
     */
    public function deleteAnnouncement(int $announcementId): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Admin access required'], 403);
        }

        $announcement = CourseAnnouncement::findOrFail($announcementId);
        $announcement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Announcement deleted successfully'
        ]);
    }

    /**
     * Get all announcements for admin dashboard
     */
    public function getAllAnnouncements(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Admin access required'], 403);
        }

        $announcements = CourseAnnouncement::with(['course', 'admin'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'content' => $announcement->content,
                    'priority' => $announcement->priority,
                    'priority_color' => $announcement->getPriorityColor(),
                    'priority_icon' => $announcement->getPriorityIcon(),
                    'is_pinned' => $announcement->is_pinned,
                    'is_active' => $announcement->isActive(),
                    'course' => [
                        'id' => $announcement->course->id,
                        'name' => $announcement->course->title,
                    ],
                    'admin' => [
                        'id' => $announcement->admin->id,
                        'name' => $announcement->admin->name,
                    ],
                    'created_at' => $announcement->created_at->toISOString(),
                    'expires_at' => $announcement->expires_at?->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'announcements' => $announcements
        ]);
    }

    /**
     * Pin/Unpin an announcement (admin only)
     */
    public function togglePin(int $announcementId): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Admin access required'], 403);
        }

        $announcement = CourseAnnouncement::findOrFail($announcementId);
        $announcement->update(['is_pinned' => !$announcement->is_pinned]);

        return response()->json([
            'success' => true,
            'is_pinned' => $announcement->is_pinned,
            'message' => $announcement->is_pinned ? 'Announcement pinned' : 'Announcement unpinned'
        ]);
    }
}
