@extends('dashboard')

@section('title', 'Suggest a Course')

@push('styles')
  @vite('resources/css/suggestion.css')
@endpush
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="suggestion-wrapper">
  <h2 class="section-title">Choose The <span>Course</span></h2>

  <!-- Step: Field Selection -->
  <div class="bubble-block">
    <p>Select the field you’re interested in</p>
    <div class="bubble-buttons">
      <button class="field-btn" data-field="IT">IT Field</button>
      <button class="field-btn" data-field="Non-IT">Non IT Field</button>
    </div>
  </div>

  <!-- Display Results -->
  <div id="course-result" class="course-result-box"></div>
</div>

@vite('resources/js/suggestion.js')
@endsection
