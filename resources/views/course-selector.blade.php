@extends('layouts.app')

@section('title', 'Course Selector - Ezyskills')

@section('content')
<div class="container mx-auto px-4 py-16">
    <h1 class="text-4xl font-bold text-center mb-12">
        Choose The <span class="text-orange-500">Course</span>
    </h1>
    <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
        <!-- Card with buttons -->
        <div class="bg-white shadow-md rounded-lg p-8 flex flex-col items-center w-full lg:w-1/2">
            <h2 class="text-xl font-semibold mb-4">Ok, let me help you</h2>
            <div class="flex gap-4">
                <a href="{{ url('/courses') }}"
                   class="bg-orange-500 text-white py-4 px-6 rounded-lg hover:bg-orange-600 transition">
                    Discover Course
                </a>
                <a href="{{ url('/suggest-course') }}"
                   class="border border-orange-500 text-orange-500 py-4 px-6 rounded-lg hover:bg-orange-50 transition">
                    Suggest Course
                </a>
            </div>
        </div>
        <!-- Illustration -->
        <div class="w-full lg:w-1/2">
            {{-- replace with your own illustration --}}
            <img src="{{ asset('images/course-selector.svg') }}" alt="Course Selector Illustration"
                 class="w-full h-auto">
        </div>
    </div>
</div>
@endsection
