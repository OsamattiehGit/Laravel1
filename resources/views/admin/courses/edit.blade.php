@extends('layouts.app')

@section('title', "Edit Course « {$course->title} »")

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('styles')
  @vite('resources/css/admin.css')
@endpush

@push('scripts')
  @vite('resources/js/admin.js')
@endpush


@section('content')
<div class="admin-container">

  <h1 class="admin-title">Edit Course « {{ $course->title }} »</h1>

  <div class="admin-row">
    <div>
      <div class="admin-card">
        <div class="admin-card-header">Edit Course</div>
        <div class="admin-card-body">
          <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('admin.courses._form-fields', [
              'course'     => $course,
              'categories' => $categories,
            ])

            <div class="admin-form-group">
              <button type="submit" class="admin-btn admin-btn-primary">Update Course</button>
              <a href="{{ route('admin.courses.index') }}" class="admin-btn admin-btn-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
