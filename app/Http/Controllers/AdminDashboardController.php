<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAnnouncement;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $coursesCount = Course::count();
        $announcementsCount = CourseAnnouncement::where('expires_at', '>', now())
            ->orWhereNull('expires_at')
            ->count();
        $usersCount = User::count();

        // Get real recent activity
        $recentActivity = [];
        
        // Get latest course
        $latestCourse = Course::latest()->first();
        if ($latestCourse) {
            $recentActivity[] = [
                'icon' => '📚',
                'text' => "Course \"{$latestCourse->title}\" was created",
                'time' => $latestCourse->created_at->diffForHumans()
            ];
        }
        
        // Get latest announcement
        $latestAnnouncement = CourseAnnouncement::latest()->first();
        if ($latestAnnouncement) {
            $recentActivity[] = [
                'icon' => '📢',
                'text' => "Announcement \"{$latestAnnouncement->title}\" was posted",
                'time' => $latestAnnouncement->created_at->diffForHumans()
            ];
        }
        
        // Get latest user
        $latestUser = User::latest()->first();
        if ($latestUser) {
            $recentActivity[] = [
                'icon' => '👥',
                'text' => "New user {$latestUser->name} registered",
                'time' => $latestUser->created_at->diffForHumans()
            ];
        }
        
        // If no activity, show default message
        if (empty($recentActivity)) {
            $recentActivity[] = [
                'icon' => '📊',
                'text' => 'Welcome to the admin dashboard!',
                'time' => 'Just now'
            ];
        }

        return view('admin.dashboard', compact(
            'coursesCount',
            'announcementsCount', 
            'usersCount',
            'recentActivity'
        ));
    }
}
