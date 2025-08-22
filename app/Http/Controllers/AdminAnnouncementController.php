<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAnnouncement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAnnouncementController extends Controller
{
    public function index()
    {
        $announcements = CourseAnnouncement::with('course')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $courses = Course::orderBy('title')->get();
        
        return view('admin.announcements.index', compact('announcements', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'expires_at' => 'nullable|date|after:now',
            'is_pinned' => 'boolean'
        ]);

        // Add admin_id and handle is_pinned properly
        $validated['admin_id'] = Auth::id();
        $validated['is_pinned'] = $request->has('is_pinned');

        $announcement = CourseAnnouncement::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Announcement created successfully!',
                'data' => $announcement
            ]);
        }

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement created successfully!');
    }

    public function update(Request $request, CourseAnnouncement $announcement)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'expires_at' => 'nullable|date|after:now',
            'is_pinned' => 'boolean'
        ]);

        $announcement->update([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'content' => $request->content,
            'priority' => $request->priority,
            'expires_at' => $request->expires_at,
            'is_pinned' => $request->has('is_pinned')
        ]);

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Announcement updated successfully!'
            ]);
        }

        // Return redirect for regular form submissions
        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    public function delete(Request $request, CourseAnnouncement $announcement)
    {
        $announcement->delete();

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Announcement deleted successfully!'
            ]);
        }

        // Return redirect for regular form submissions
        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }

    public function togglePin(CourseAnnouncement $announcement)
    {
        $announcement->update([
            'is_pinned' => !$announcement->is_pinned
        ]);

        return response()->json([
            'success' => true,
            'is_pinned' => $announcement->is_pinned
        ]);
    }

    public function list()
    {
        $announcements = CourseAnnouncement::with('course')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $html = view('admin.announcements._table', compact('announcements'))->render();
        
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }
}
