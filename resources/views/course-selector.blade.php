@extends('layouts.app')

@section('title', 'Course Selector')

@section('content')
  @vite('resources/css/course-selector.css')

<div class="selector-page">
  <h1 class="title">Choose The <span>Course</span></h1>

  <div class="selector-grid">
    <!-- Left card -->
    <div class="card">
      <h2 class="card-title">Ok, Let me help you</h2>
      <div class="button-group">
        <a href="{{ url('/courses') }}" class="button discover">Discover Course</a>
        <a href="{{ route('suggest.course') }}" class="button suggest">Suggest Course</a>

      </div>
    </div>

    <!-- Right illustration -->
    <div class="illustration">
      <img src="{{ asset('images/course-selector.svg') }}" alt="Course Selector">
    </div>
  </div>
</div>
@endsection
