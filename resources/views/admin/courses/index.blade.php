@extends('layouts.app')

@section('title', 'Admin Course Manager')

@push('styles')
  @vite('resources/css/admin.css')
@endpush


@section('content')
<div class="admin-container">

  {{-- Back to Dashboard Button --}}
  <div class="admin-back-nav mb-3">
    <a href="{{ route('admin.dashboard') }}" class="admin-back-btn">
      ← Back to Dashboard
    </a>
  </div>

  <h1 class="admin-title">Admin Course Manager</h1>

  {{-- Admin Navigation --}}
  <div class="admin-navigation mb-4">
    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link">Dashboard</a>
    <a href="{{ route('admin.courses.index') }}" class="admin-nav-link active">Courses</a>
    <a href="{{ route('admin.announcements.index') }}" class="admin-nav-link">Announcements</a>
  </div>

  <div class="admin-row">

    {{-- Add New Course --}}
    <div>
      <div class="admin-card">
        <div class="admin-card-header">Add New Course</div>
        <div class="admin-card-body">
          <form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data">
            @csrf

            @include('admin.courses._form-fields', [
              'course'     => new App\Models\Course,
              'categories' => $categories,
            ])

            <div class="admin-form-group">
              <button type="submit" class="admin-btn admin-btn-primary">Save Course</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Current Courses --}}
    <div>
      <div class="admin-card">
        <div class="admin-card-header">Current Courses</div>
        <div class="admin-card-body">
          <table class="admin-table">
            @include('admin.courses._table', ['courses' => $courses])
          </table>

          <div class="mt-3">
            {{ $courses->links() }}
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
