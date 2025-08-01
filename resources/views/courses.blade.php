@extends('layouts.app')

@section('title', 'Courses List')

{{-- Tell Vite to build & inject our CSS + JS bundles: --}}
@vite([
  'resources/css/courses.css',
  'resources/js/courses.js',
])

@section('content')
<div class="courses-container">
  <div class="courses-header">
    <h1 class="courses-title">
      <span class="courses-title--orange">Courses</span>
      <span class="courses-title--navy">List</span>
    </h1>

    <div class="search-box">
      <input
        id="search-box"
        type="text"
        placeholder="Search The Course Here…"
        autocomplete="off"
      />
      <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path fill="#888" d="M15.5 14h-0.79l-0.28-0.27...
        "/><!-- trimmed for brevity -->
      </svg>
    </div>

    <div class="filters">
      <button class="filter-btn active" data-filter="all">All</button>
      <button class="filter-btn"        data-filter="opened">Opened</button>
      <button class="filter-btn"        data-filter="soon">Coming Soon</button>
      <button class="filter-btn"        data-filter="archived">Archived</button>
    </div>

    <div class="sort-select">
      <select id="sort-select">
        <option value="popular">Popular Class</option>
        <option value="newest">Newest</option>
        <option value="title">Title A–Z</option>
      </select>
    </div>
  </div>

  <div id="course-list" class="course-grid"></div>
  <div id="pagination"    class="pagination"></div>
</div>
@endsection
