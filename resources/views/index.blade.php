@extends('layouts.app')

@section('title', 'Course List')

@section('content')
<header>
    <h1>Courses <span>List</span></h1>
</header>
<div id="course-list" class="course-grid"></div>
@endsection

@push('scripts')
<script src="{{ asset('js/main.js') }}"></script>
@endpush
@foreach($courses as $course)
  <div class="course-card">
    {{-- … --}}
    <a href="{{ route('courses.show', $course) }}"
       class="btn btn-orange">
      Enroll Now
    </a>
  </div>
@endforeach
