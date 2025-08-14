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
    // Validation
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
        'projects.*.icon' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        'projects.*.title'=> 'nullable|string|max:255',
        'projects.*.subtitle'=>'nullable|string|max:255',

        'tools'           => 'nullable|array',
        'tools.*.icon'    => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        'tools.*.name'    => 'nullable|string|max:255',

        'image'           => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
        'curriculum'      => 'nullable|mimes:pdf,doc,docx|max:5120',

        // structured content
        'course_content_details'                => 'nullable|array',
        'course_content_details.*.section'      => 'required_with:course_content_details|string|max:255',
        'course_content_details.*.items'        => 'nullable|array',
        'course_content_details.*.items.*.type' => 'required|in:text,image,video',
        'course_content_details.*.items.*.value'=> 'required|string|max:2000',
    ]);

    // New category
    if ($v['category_id'] === '__new__') {
        if (empty($v['new_category'])) {
            return back()->withErrors(['new_category' => 'Please enter the new category name.'])->withInput();
        }
        $cat = Category::firstOrCreate(['name' => $v['new_category']]);
        $v['category_id'] = $cat->id;
    }

    // Image
    if ($request->hasFile('image')) {
        $v['image'] = $request->file('image')->store('courses','public');
    }

    // Outline & objectives (old fields)
    $objectivesArr = array_filter(array_map('trim', explode(',', $v['objectives'] ?? '')));
    $outlineArr    = array_filter(array_map('trim', explode(',', $v['course_content'] ?? '')));

    // Projects
    $projects = [];
    foreach ($request->input('projects', []) as $i => $proj) {
        $iconPath = $proj['_icon'] ?? null;
        if ($file = $request->file("projects.$i.icon")) {
            $iconPath = $file->store('courses','public');
        }
        $projects[] = [
            'icon'     => $iconPath,
            'title'    => $proj['title']    ?? '',
            'subtitle' => $proj['subtitle'] ?? '',
        ];
    }

    // Tools
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

    // ✅ Normalize structured course content
    $details = array_values(array_map(function ($sec) {
        $items = array_values(array_map(function ($it) {
            $type  = in_array($it['type'] ?? 'text', ['text','image','video']) ? $it['type'] : 'text';
            $value = trim((string)($it['value'] ?? ''));
            return ['type' => $type, 'value' => $value];
        }, $sec['items'] ?? []));
        return ['section' => trim((string)($sec['section'] ?? '')), 'items' => $items];
    }, $request->input('course_content_details', [])));

    // Create
    $course = Course::create([
        'title'                  => $v['title'],
        'description'            => $v['description'],
        'instructor'             => $v['instructor'],
        'category_id'            => $v['category_id'],
        'status'                 => $v['status'],
        'objectives'             => $objectivesArr,
        'course_content'         => $outlineArr,            // old outline list (optional)
        'projects'               => $projects,
        'tools'                  => $tools,
        'image'                  => $v['image'] ?? null,
        'user_id'                => auth()->id(),
        'course_content_details' => $details,               // ✅ correct structured data
    ]);

    if ($request->hasFile('curriculum')) {
        $course->update([
            'curriculum' => $request->file('curriculum')->store('curriculums', 'public'),
        ]);
    }

    return redirect()->route('admin.courses.index')->with('success','Course added successfully.');
}

    public function edit(Course $course)
    {
        $categories = Category::all();
        return view('admin.courses.edit', compact('course','categories'));
    }

 public function update(Request $request, Course $course)
{
    // Validation (same as store)
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
        'projects.*.icon' => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
        'projects.*.title'=> 'nullable|string|max:255',
        'projects.*.subtitle'=>'nullable|string|max:255',

        'tools'           => 'nullable|array',
        'tools.*.icon'    => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
        'tools.*.name'    => 'nullable|string|max:255',

        'image'           => 'nullable|mimes:png,jpg,jpeg,svg|max:2048',
        'curriculum'      => 'nullable|mimes:pdf,doc,docx|max:5120',

        'course_content_details'                => 'nullable|array',
        'course_content_details.*.section'      => 'required_with:course_content_details|string|max:255',
        'course_content_details.*.items'        => 'nullable|array',
        'course_content_details.*.items.*.type' => 'required|in:text,image,video',
        'course_content_details.*.items.*.value'=> 'required|string|max:2000',
    ]);

    // New category
    if ($v['category_id'] === '__new__') {
        if (empty($v['new_category'])) {
            return back()->withErrors(['new_category' => 'Please enter the new category name.'])->withInput();
        }
        $cat = Category::firstOrCreate(['name' => $v['new_category']]);
        $v['category_id'] = $cat->id;
    }

    // Replace image
    if ($request->hasFile('image')) {
        if ($course->image) Storage::disk('public')->delete($course->image);
        $v['image'] = $request->file('image')->store('courses','public');
    }

    // Outline & objectives
    $objectivesArr = array_filter(array_map('trim', explode(',', $v['objectives'] ?? '')));
    $outlineArr    = array_filter(array_map('trim', explode(',', $v['course_content'] ?? '')));

    // Projects
    $projects = [];
    foreach ($request->input('projects', []) as $i => $proj) {
        $iconPath = $proj['_icon'] ?? null;
        if ($file = $request->file("projects.$i.icon")) {
            if ($iconPath) Storage::disk('public')->delete($iconPath);
            $iconPath = $file->store('courses','public');
        }
        $projects[] = [
            'icon'     => $iconPath,
            'title'    => $proj['title']    ?? '',
            'subtitle' => $proj['subtitle'] ?? '',
        ];
    }

    // Tools
    $tools = [];
    foreach ($request->input('tools', []) as $i => $t) {
        $iconPath = $t['_icon'] ?? null;
        if ($file = $request->file("tools.$i.icon")) {
            if ($iconPath) Storage::disk('public')->delete($iconPath);
            $iconPath = $file->store('courses','public');
        }
        $tools[] = [
            'icon' => $iconPath,
            'name' => $t['name'] ?? '',
        ];
    }

    // ✅ Normalize structured course content
    $details = array_values(array_map(function ($sec) {
        $items = array_values(array_map(function ($it) {
            $type  = in_array($it['type'] ?? 'text', ['text','image','video']) ? $it['type'] : 'text';
            $value = trim((string)($it['value'] ?? ''));
            return ['type' => $type, 'value' => $value];
        }, $sec['items'] ?? []));
        return ['section' => trim((string)($sec['section'] ?? '')), 'items' => $items];
    }, $request->input('course_content_details', [])));

    // Update
    $course->update([
        'title'                  => $v['title'],
        'description'            => $v['description'],
        'instructor'             => $v['instructor'],
        'category_id'            => $v['category_id'],
        'status'                 => $v['status'],
        'objectives'             => $objectivesArr,
        'course_content'         => $outlineArr,
        'projects'               => $projects,
        'tools'                  => $tools,
        'image'                  => $v['image'] ?? $course->image,
        'user_id'                => auth()->id(),
        'course_content_details' => $details,   // ✅ single, correct write
    ]);

    if ($request->hasFile('curriculum')) {
        if ($course->curriculum) Storage::disk('public')->delete($course->curriculum);
        $course->update([
            'curriculum' => $request->file('curriculum')->store('curriculums', 'public'),
        ]);
    }

    return redirect()->route('admin.courses.index')->with('success','Course updated successfully.');
}


    public function destroy(Course $course)
    {
        if ($course->curriculum) {
    Storage::disk('public')->delete($course->curriculum);
}

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
