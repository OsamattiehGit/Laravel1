<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * GET /courses
     * JSON for API, otherwise returns courses.index Blade view.
     */
    public function index(Request $request)
    {
        $q      = $request->input('q', '');
        $filter = $request->input('filter', 'all');
        $sort   = $request->input('sort', 'popular');

        $query = Course::withCount('enrollments');

        // 1) Search
        if ($q !== '') {
            $query->where(function($qb) use($q){
                $qb->where('title', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // 2) Filter
        if (in_array($filter, ['opened','soon','archived'])) {
            $query->where('status', $filter);
        }

        // 3) Sort
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at','desc');
                break;
            case 'title':
                $query->orderBy('title','asc');
                break;
            case 'popular':
            default:
                $query->orderBy('enrollments_count','desc');
                break;
        }

        // 4) Paginate
        $perPage   = 8;
        $page      = (int)$request->input('page', 1);
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        // 5) JSON for API
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'data' => $paginator->items(),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page'    => $paginator->lastPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                ],
            ]);
        }

        // Blade view
        return view('courses', [
            'courses' => $paginator,
            'q'       => $q,
            'filter'  => $filter,
            'sort'    => $sort,
        ]);
    }

    /**
     * GET /course/{id}
     * JSON for API, otherwise returns course.blade.php
     */
 public function show(Request $request, $id)
    {
        $course = Course::with('category')
                        ->withCount('enrollments')
                        ->findOrFail($id);

        // Ensure JSON columns are arrays
        foreach (['objectives','course_content','projects','tools'] as $field) {
            if (! is_array($course->{$field})) {
                $course->{$field} = json_decode($course->getOriginal($field) ?? '[]', true) ?: [];
            }
        }

        // Check if user is enrolled in this course
        $isEnrolled = false;
        if (auth()->check()) {
            $isEnrolled = auth()->user()->enrollments()
                ->where('course_id', $course->id)
                ->exists();
        }

        return view('course', compact('course', 'isEnrolled'));
    }

    /**
     * POST /api/courses
     * Creates a new Course (API only).
     */
    public function store(Request $request)
    {
        $v = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'category_id'    => 'required|exists:categories,id',
            'instructor'     => 'required|string|max:255',
            'status'         => 'required|in:opened,soon,archived',
            'objectives'     => 'nullable|array',
            'course_content' => 'nullable|array',
            'projects'       => 'nullable|array',
            'tools'          => 'nullable|array',
            'user_id'        => 'nullable|integer',
            'image'          => 'nullable|image|mimes:svg,png,jpg,jpeg|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $v['image'] = $request->file('image')->store('courses','public');
        }

        // JSON-encode array fields
        $v['objectives']     = json_encode($v['objectives']     ?? []);
        $v['course_content'] = json_encode($v['course_content'] ?? []);
        $v['projects']       = json_encode($v['projects']       ?? []);
        $v['tools']          = json_encode($v['tools']          ?? []);
        $v['user_id']        = $v['user_id'] ?? auth()->id() ?? 1;

        $course = Course::create($v);

        return response()->json(['data' => $course], 201);
    }

    /**
     * PUT /api/courses/{id}
     * Updates an existing Course (API only).
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $v = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'category_id'    => 'required|exists:categories,id',
            'instructor'     => 'required|string|max:255',
            'status'         => 'required|in:opened,soon,archived',
            'objectives'     => 'nullable|array',
            'course_content' => 'nullable|array',
            'projects'       => 'nullable|array',
            'tools'          => 'nullable|array',
            'image'          => 'nullable|image|mimes:svg,png,jpg,jpeg|max:2048',
        ]);

        // Replace image if uploaded
        if ($request->hasFile('image')) {
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            $v['image'] = $request->file('image')->store('courses','public');
        }

        $v['objectives']     = json_encode($v['objectives']     ?? []);
        $v['course_content'] = json_encode($v['course_content'] ?? []);
        $v['projects']       = json_encode($v['projects']       ?? []);
        $v['tools']          = json_encode($v['tools']          ?? []);

        $course->update($v);

        return response()->json([
            'data' => $course->fresh()->load('category')
        ]);
    }

    /**
     * DELETE /api/courses/{id}
     * Deletes a Course (API only).
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }
    public function showSuggestionFlow()
{
    return view('courses.suggestion-flow');
}

public function getSuggestedCourses(Request $request)
{
    $field = $request->input('field'); // "IT Field" or "Non IT Field"

    if ($field === 'IT Field') {
        $categoryIds = \App\Models\Category::where('type', 'IT')->pluck('id');
    } else {
        $categoryIds = \App\Models\Category::where('type', 'Non-IT')->pluck('id');
    }

    $courses = Course::whereIn('category_id', $categoryIds)->pluck('title');

    return response()->json(['courses' => $courses]);
}






}
