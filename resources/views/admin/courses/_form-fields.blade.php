@push('styles')
  @vite('resources/css/admin.css')
@endpush

@php
  $oldObj = old('objectives', $course->objectives ?? '');
  $oldCnt = old('course_content', $course->course_content ?? '');
@endphp

<div class="admin-form-group">
  <label for="title">Title</label>
  <input
    type="text"
    name="title"
    id="title"
    class="admin-form-control @error('title') is-invalid @enderror"
    value="{{ old('title', $course->title) }}"
  >
  @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="admin-form-group">
  <label for="instructor">Instructor</label>
  <input
    type="text"
    name="instructor"
    id="instructor"
    class="admin-form-control @error('instructor') is-invalid @enderror"
    value="{{ old('instructor', $course->instructor) }}"
  >
  @error('instructor') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="admin-form-group">
  <label for="category_id">Category</label>
  <select
    name="category_id"
    id="category_id"
    class="admin-form-select @error('category_id') is-invalid @enderror"
  >
    <option value="">— Choose —</option>
    @foreach($categories as $cat)
      <option
        value="{{ $cat->id }}"
        {{ old('category_id', $course->category_id) == $cat->id ? 'selected' : '' }}
      >{{ $cat->name }}</option>
    @endforeach
  </select>
  @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<!-- Course Type -->
<div class="admin-form-group">
  <label for="type">Type</label>
  <select
    name="type"
    id="type"
    class="admin-form-select @error('type') is-invalid @enderror"
  >
    <option value="IT" {{ old('type', $course->type ?? '') === 'IT' ? 'selected' : '' }}>IT</option>
    <option value="Non-IT" {{ old('type', $course->type ?? '') === 'Non-IT' ? 'selected' : '' }}>Non-IT</option>
  </select>
  @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>



<div class="admin-form-group">
  <label for="status">Status</label>
  <select
    name="status"
    id="status"
    class="admin-form-select @error('status') is-invalid @enderror"
  >
    <option value="opened"  {{ old('status', $course->status) == 'opened'  ? 'selected' : '' }}>Opened</option>
    <option value="soon"    {{ old('status', $course->status) == 'soon'    ? 'selected' : '' }}>Coming Soon</option>
    <option value="archived"{{ old('status', $course->status) == 'archived'? 'selected' : '' }}>Archived</option>
  </select>
  @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="admin-form-group">
  <label for="image">Image (SVG/PNG/JPG)</label>
  <input
    type="file"
    name="image"
    id="image"
    class="admin-form-control @error('image') is-invalid @enderror"
  >
  @if($course->image)
    <p class="mt-2">
      <img src="{{ Storage::url($course->image) }}"
           alt="Current"
           style="max-height:60px; border:1px solid #CCC; padding:2px;">
    </p>
  @endif
  @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="admin-form-group">
  <label for="description">Description</label>
  <textarea
    name="description"
    id="description"
    class="admin-form-textarea @error('description') is-invalid @enderror"
  >{{ old('description', $course->description) }}</textarea>
  @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="admin-form-group">
  <label for="objectives">Objectives <small>(comma‑separated)</small></label>
  <textarea
    name="objectives"
    id="objectives"
    class="admin-form-textarea @error('objectives') is-invalid @enderror"
  >{{ is_string($oldObj) ? $oldObj : implode(',', $oldObj) }}</textarea>
  @error('objectives') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="admin-form-group">
  <label for="course_content">Course Content <small>(comma‑separated)</small></label>
  <textarea
    name="course_content"
    id="course_content"
    class="admin-form-textarea @error('course_content') is-invalid @enderror"
  >{{ is_string($oldCnt) ? $oldCnt : implode(',', $oldCnt) }}</textarea>
  @error('course_content') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
{{-- make absolutely sure $projects & $tools are arrays --}}
@php
  // Projects
  $rawProjects = old('projects', $course->projects ?? []);
  if (is_string($rawProjects)) {
    $projects = json_decode($rawProjects, true) ?: [];
  } elseif (is_array($rawProjects)) {
    $projects = $rawProjects;
  } else {
    $projects = [];
  }

  // Tools
  $rawTools = old('tools', $course->tools ?? []);
  if (is_string($rawTools)) {
    $tools = json_decode($rawTools, true) ?: [];
  } elseif (is_array($rawTools)) {
    $tools = $rawTools;
  } else {
    $tools = [];
  }

  // Guarantee at least one blank row
  if (count($projects) === 0) {
    $projects[] = ['icon'=>'','title'=>'','subtitle'=>''];
  }
  if (count($tools) === 0) {
    $tools[] = ['icon'=>'','name'=>''];
  }
@endphp

{{-- Projects --}}
<div class="admin-form-group">
  <label>Projects</label>
  <div id="projects-wrapper">
    {{-- always show at least one blank row --}}
    @if(count($projects) === 0)
      @php $projects[] = ['icon'=>'','title'=>'','subtitle'=>'']; @endphp
    @endif

    @foreach($projects as $i => $proj)
      <div class="repeatable-item mb-3">
        {{-- preview existing icon --}}
        @if(!empty($proj['icon']))
          <p>
            <img src="{{ Storage::url($proj['icon']) }}"
                 alt=""
                 style="max-height:50px;">
          </p>
        @endif

        {{-- keep old path --}}
        <input type="hidden"
               name="projects[{{ $i }}][_icon]"
               value="{{ $proj['icon'] }}">

        {{-- upload new icon --}}
        <input type="file"
               name="projects[{{ $i }}][icon]"
               class="admin-form-control mb-1 @error('projects.'.$i.'.icon') is-invalid @enderror">
        @error('projects.'.$i.'.icon')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        {{-- title --}}
        <input type="text"
               name="projects[{{ $i }}][title]"
               placeholder="Project title"
               value="{{ $proj['title'] ?? '' }}"
               class="admin-form-control mb-1 @error('projects.'.$i.'.title') is-invalid @enderror">
        @error('projects.'.$i.'.title')<div class="invalid-feedback">{{ $message }}</div>@enderror

        {{-- subtitle --}}
        <input type="text"
               name="projects[{{ $i }}][subtitle]"
               placeholder="Subtitle"
               value="{{ $proj['subtitle'] ?? '' }}"
               class="admin-form-control mb-1 @error('projects.'.$i.'.subtitle') is-invalid @enderror">
        @error('projects.'.$i.'.subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror

        <button type="button"
                class="admin-btn admin-btn-danger remove-project">
          Remove
        </button>
        <hr>
      </div>
    @endforeach
  </div>
  <button type="button"
          id="add-project"
          class="admin-btn admin-btn-secondary">
    + Add Project
  </button>
</div>

{{-- Tools --}}
<div class="admin-form-group">
  <label>Tools &amp; Platforms</label>
  <div id="tools-wrapper">
    @if(count($tools) === 0)
      @php $tools[] = ['icon'=>'','name'=>'']; @endphp
    @endif

    @foreach($tools as $i => $tool)
      <div class="repeatable-item mb-3">
        @if(!empty($tool['icon']))
          <p>
            <img src="{{ Storage::url($tool['icon']) }}"
                 alt=""
                 style="max-height:50px;">
          </p>
        @endif

        <input type="hidden"
               name="tools[{{ $i }}][_icon]"
               value="{{ $tool['icon'] }}">

        <input type="file"
               name="tools[{{ $i }}][icon]"
               class="admin-form-control mb-1 @error('tools.'.$i.'.icon') is-invalid @enderror">
        @error('tools.'.$i.'.icon')<div class="invalid-feedback">{{ $message }}</div>@enderror

        <input type="text"
               name="tools[{{ $i }}][name]"
               placeholder="Tool name"
               value="{{ $tool['name'] ?? '' }}"
               class="admin-form-control mb-1 @error('tools.'.$i.'.name') is-invalid @enderror">
        @error('tools.'.$i.'.name')<div class="invalid-feedback">{{ $message }}</div>@enderror

        <button type="button"
                class="admin-btn admin-btn-danger remove-tool">
          Remove
        </button>
        <hr>
      </div>
    @endforeach
  </div>
  <button type="button"
          id="add-tool"
          class="admin-btn admin-btn-secondary">
    + Add Tool
  </button>
</div>
