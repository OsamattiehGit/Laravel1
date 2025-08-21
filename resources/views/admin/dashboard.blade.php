@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
  @vite('resources/css/admin.css')
@endpush

@section('content')
<div class="admin-container">
  <h1 class="admin-title">Admin Dashboard</h1>

  {{-- Admin Navigation --}}
  <div class="admin-navigation mb-4">
    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link active">Dashboard</a>
    <a href="{{ route('admin.courses.index') }}" class="admin-nav-link">Courses</a>
    <a href="{{ route('admin.announcements.index') }}" class="admin-nav-link">Announcements</a>
  </div>

  <div class="admin-row">
    {{-- Quick Stats --}}
    <div>
      <div class="admin-card">
        <div class="admin-card-header">Quick Stats</div>
        <div class="admin-card-body">
          <div class="admin-stat">
            <div class="admin-stat-number">{{ $coursesCount ?? 0 }}</div>
            <div class="admin-stat-label">Total Courses</div>
          </div>
          <div class="admin-stat">
            <div class="admin-stat-number">{{ $announcementsCount ?? 0 }}</div>
            <div class="admin-stat-label">Active Announcements</div>
          </div>
          <div class="admin-stat">
            <div class="admin-stat-number">{{ $usersCount ?? 0 }}</div>
            <div class="admin-stat-label">Total Users</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Quick Actions --}}
    <div>
      <div class="admin-card">
        <div class="admin-card-header">Quick Actions</div>
        <div class="admin-card-body">
          <div class="admin-quick-actions">
            <a href="{{ route('admin.courses.index') }}" class="admin-quick-action">
              <span class="admin-quick-action-icon">📚</span>
              <span class="admin-quick-action-text">Manage Courses</span>
            </a>
            <a href="{{ route('admin.announcements.index') }}" class="admin-quick-action">
              <span class="admin-quick-action-icon">📢</span>
              <span class="admin-quick-action-text">Create Announcement</span>
            </a>
            
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Recent Activity --}}
  <div class="admin-card mt-4">
    <div class="admin-card-header">Recent Activity</div>
    <div class="admin-card-body">
      <div class="admin-activity-list">
        @forelse($recentActivity ?? [] as $activity)
          <div class="admin-activity-item">
            <div class="admin-activity-icon">{{ $activity['icon'] }}</div>
            <div class="admin-activity-content">
              <div class="admin-activity-text">{{ $activity['text'] }}</div>
              <div class="admin-activity-time">{{ $activity['time'] }}</div>
            </div>
          </div>
        @empty
          <div class="admin-activity-empty">
            <p>No recent activity to display.</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
