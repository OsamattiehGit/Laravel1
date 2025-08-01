<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCourseController extends Controller
{
    public function index()
    {
        $courses    = Course::with('category')->paginate(10);
        $categories = Category::all();
        return view('admin.courses.index', compact('courses', 'categories'));
    }

    public function store(Request $request)
    {
        // 1) Validation
        $v = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'instructor'      => 'required|string|max:255',
            'category_id'     => 'required',
            'new_category'    => 'nullable|string|max:50',
            'status'          => 'required|in:opened,soon,archived',
            'objectives'      => 'nullable|string',
            'course_content'  => 'nullable|string',
            'projects'        => 'nullable|array',
            'projects.*.icon' => 'nullable|image|mimes:svg,png,jpg,jpeg|max:2048',
            'projects.*.title'=> 'nullable|string|max:255',
            'projects.*.subtitle'=>'nullable|string|max:255',
            'tools'           => 'nullable|array',
            'tools.*.icon'    => 'nullable|image|mimes:svg,png,jpg,jpeg|max:2048',
            'tools.*.name'    => 'nullable|string|max:255',
            'image'           => 'nullable|image|mimes:svg,png,jpg,jpeg|max:2048',
        ]);

        // 2) Handle “Add New Category”
        if ($v['category_id'] === '__new__') {
            if (empty($v['new_category'])) {
                return back()
                    ->withErrors(['new_category' => 'Please enter the new category name.'])
                    ->withInput();
            }
            $cat = Category::firstOrCreate(['name' => $v['new_category']]);
            $v['category_id'] = $cat->id;
        }

        // 3) Handle course image
        if ($request->hasFile('image')) {
            $v['image'] = $request->file('image')->store('courses','public');
        }

        // 4) Parse objectives & content into arrays
        $objectives = array_filter(
          array_map('trim', explode(',', $v['objectives'] ?? ''))
        );
        $content = array_filter(
          array_map('trim', explode(',', $v['course_content'] ?? ''))
        );

        // 5) Handle projects
        $projects = [];
        foreach ($request->input('projects', []) as $i => $proj) {
            // carry over existing icon path from hidden `_icon` field
            $iconPath = $proj['_icon'] ?? null;

            // if a new file was uploaded for this project
            if ($file = $request->file("projects.$i.icon")) {
                $iconPath = $file->store('courses','public');
            }

            $projects[] = [
                'icon'     => $iconPath,
                'title'    => $proj['title']    ?? '',
                'subtitle' => $proj['subtitle'] ?? '',
            ];
        }

        // 6) Handle tools
        $tools = [];
        foreach ($request->input('tools', []) as $i => $t) {
            $iconPath = $t['_icon'] ?? null;
            if ($file = $request->file("tools.$i.icon")) {
                $iconPath = $file->store('courses','public');
            }
            $tools[] = [
                'icon' => $iconPath,
                'name' => $t['name'] ?? '',
            ];
        }

        // 7) Persist
        Course::create([
            'title'          => $v['title'],
            'description'    => $v['description'],
            'instructor'     => $v['instructor'],
            'category_id'    => $v['category_id'],
            'status'         => $v['status'],
            'objectives'     => $objectives,
            'course_content' => $content,
            'projects'       => $projects,
            'tools'          => $tools,
            'image'          => $v['image'] ?? null,
            'user_id'        => auth()->id(),
        ]);

        return redirect()
            ->route('admin.courses.index')
            ->with('success','Course added successfully.');
    }

    public function edit(Course $course)
    {
        $categories = Category::all();
        return view('admin.courses.edit', compact('course','categories'));
    }

    public function update(Request $request, Course $course)
    {
        // 1) Validation (same as store)
        $v = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'instructor'      => 'required|string|max:255',
            'category_id'     => 'required',
            'new_category'    => 'nullable|string|max:50',
            'status'          => 'required|in:opened,soon,archived',
            'objectives'      => 'nullable|string',
            'course_content'  => 'nullable|string',
            'projects'        => 'nullable|array',
            'projects.*.icon' => 'nullable|image|mimes:svg,png,jpg,jpeg|max:2048',
            'projects.*.title'=> 'nullable|string|max:255',
            'projects.*.subtitle'=>'nullable|string|max:255',
            'tools'           => 'nullable|array',
            'tools.*.icon'    => 'nullable|image|mimes:svg,png,jpg,jpeg|max:2048',
            'tools.*.name'    => 'nullable|string|max:255',
            'image'           => 'nullable|image|mimes:svg,png,jpg,jpeg|max:2048',
        ]);

        // 2) Handle new category
        if ($v['category_id'] === '__new__') {
            if (empty($v['new_category'])) {
                return back()
                    ->withErrors(['new_category' => 'Please enter the new category name.'])
                    ->withInput();
            }
            $cat = Category::firstOrCreate(['name' => $v['new_category']]);
            $v['category_id'] = $cat->id;
        }

        // 3) Replace course image if needed
        if ($request->hasFile('image')) {
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            $v['image'] = $request->file('image')->store('courses','public');
        }

        // 4) Re-parse objectives & content
        $objectives = array_filter(
          array_map('trim', explode(',', $v['objectives'] ?? ''))
        );
        $content = array_filter(
          array_map('trim', explode(',', $v['course_content'] ?? ''))
        );

        // 5) Re-handle projects
        $projects = [];
        foreach ($request->input('projects', []) as $i => $proj) {
            $iconPath = $proj['_icon'] ?? null;
            if ($file = $request->file("projects.$i.icon")) {
                if ($iconPath) {
                    Storage::disk('public')->delete($iconPath);
                }
                $iconPath = $file->store('courses','public');
            }
            $projects[] = [
                'icon'     => $iconPath,
                'title'    => $proj['title']    ?? '',
                'subtitle' => $proj['subtitle'] ?? '',
            ];
        }

        // 6) Re-handle tools
        $tools = [];
        foreach ($request->input('tools', []) as $i => $t) {
            $iconPath = $t['_icon'] ?? null;
            if ($file = $request->file("tools.$i.icon")) {
                if ($iconPath) {
                    Storage::disk('public')->delete($iconPath);
                }
                $iconPath = $file->store('courses','public');
            }
            $tools[] = [
                'icon' => $iconPath,
                'name' => $t['name'] ?? '',
            ];
        }

        // 7) Persist update
        $course->update([
            'title'          => $v['title'],
            'description'    => $v['description'],
            'instructor'     => $v['instructor'],
            'category_id'    => $v['category_id'],
            'status'         => $v['status'],
            'objectives'     => $objectives,
            'course_content' => $content,
            'projects'       => $projects,
            'tools'          => $tools,
            'image'          => $v['image'] ?? $course->image,
            'user_id'        => auth()->id(),
        ]);

        return redirect()
            ->route('admin.courses.index')
            ->with('success','Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }
        // Also remove any project/tool icons if you like…

        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success','Course deleted successfully.');
    }
}
