@extends('layouts.app')

@section('title', 'Admin Course Manager')

@push('styles')
  @vite('resources/css/admin.css')
@endpush


@section('content')
<div class="admin-container">

  <h1 class="admin-title">Admin Course Manager</h1>

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
