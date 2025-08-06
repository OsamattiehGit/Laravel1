{{-- resources/views/profile.blade.php --}}
@extends('layouts.app')

@push('styles')
<style>
    .profile-header {
        background: #003366;
        color: #fff;
        border-radius: 0 0 36px 0;
        padding: 48px 0 38px 0;
        margin-bottom: 40px;
        text-align: left;
    }
    .profile-header .profile-title {
        font-family: 'Poppins', sans-serif;
        font-size: 2.4rem;
        font-weight: 700;
        margin-bottom: 10px;
        letter-spacing: -1px;
    }
    .profile-header .profile-meta {
        font-size: 1.2rem;
        color: #fff8ee;
        margin-bottom: 7px;
    }
    .profile-balance {
        background: #ff942f;
        color: #fff;
        font-weight: 600;
        padding: 8px 24px;
        border-radius: 10px;
        display: inline-block;
        font-size: 1.11rem;
        margin-top: 7px;
        letter-spacing: 1px;
    }
    .enrolled-section-title {
        font-family: 'Poppins', sans-serif;
        color: #003366;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 28px;
        letter-spacing: 1px;
    }
    .enrolled-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 22px;
    }
    .enrolled-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 18px 0 rgba(44, 62, 80, 0.10);
        padding: 25px 16px 18px 16px;
        text-align: center;
        transition: box-shadow .22s;
        position: relative;
    }
    .enrolled-card:hover {
        box-shadow: 0 8px 30px 0 rgba(44, 62, 80, 0.13);
    }
    .enrolled-img {
            display: block;
    margin-left: auto;
    margin-right: auto;
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 10px;
        background: #f4f4fa;
        margin-bottom: 15px;
        border: 2px solid #e4e8ef;
    }
    .enrolled-title {
        color: #003366;
        font-family: 'Poppins', sans-serif;
        font-size: 1.13rem;
        font-weight: 600;
        margin-bottom: 6px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    .enrolled-desc {
        font-size: 0.97rem;
        color: #555b6a;
        min-height: 36px;
        margin-bottom: 12px;
    }
    .enrolled-btn {
        display: inline-block;
        background: #ff942f;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        font-size: 1rem;
        padding: 7px 21px;
        margin-top: 8px;
        text-decoration: none;
        transition: background .15s;
    }
    .enrolled-btn:hover {
        background: #f37021;
        color: #fff;
    }
    @media (max-width: 767px) {
        .profile-header {
            padding: 35px 0 25px 0;
            border-radius: 0 0 20px 0;
        }
        .profile-header .profile-title { font-size: 1.5rem; }
        .enrolled-section-title { font-size: 1.15rem; }
        .enrolled-img { width: 56px; height: 56px; }
    }
</style>
@endpush

@section('content')
<div class="profile-header mb-4">
    <div class="container">
        <div class="profile-title mb-2">
            <i class="bi bi-person-fill" style="font-size: 2rem; vertical-align: middle; color: #ff942f;"></i>
            Profile
        </div>
        <div class="profile-meta">
            <strong>Username:</strong> {{ auth()->user()->name ?? 'User' }}
        </div>
        <div class="profile-meta">
            <strong>Email:</strong> {{ auth()->user()->email }}
        </div>
        <div class="profile-balance">
            Remaining Balance:
            <span>{{ auth()->user()->course_balance ?? 0 }}</span> credits
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="enrolled-section-title">Your Enrolled Courses</div>
    @if(count($enrollments ?? []) > 0)
    <div class="enrolled-grid">
        @foreach($enrollments as $enrollment)
            @php $course = $enrollment->course; @endphp
            <div class="enrolled-card">
                <img src="{{ $course->image ? Storage::url($course->image) : asset('images/default-course.png') }}"
                     class="enrolled-img"
                     alt="{{ $course->title }}">
                <div class="enrolled-title">{{ $course->title }}</div>
                <div class="enrolled-desc">
                    {{ \Illuminate\Support\Str::limit($course->description, 80) }}
                </div>
                <a href="{{ route('course.show', $course->id) }}" class="enrolled-btn">Go to Course</a>
            </div>
        @endforeach
    </div>
    @else
        <div class="alert alert-info text-center">
            You are not enrolled in any courses yet.
        </div>
    @endif
</div>
@endsection
