@extends('dashboard')

@section('title', 'Suggest a Course')

@push('styles')
    @vite('resources/css/suggestion.css')
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <h1 class="section-title">
        <span class="blue">Choose</span> <span class="orange">the Course</span>
    </h1>
<div class="suggestion-wrapper">


    <div id="chat-log" class="chat-log">
        {{-- Chat bubbles will be appended here dynamically --}}
    </div>
</div>

@vite('resources/js/suggestion.js')
@endsection
