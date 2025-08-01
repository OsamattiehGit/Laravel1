@extends('layouts.app')

@push('styles')
  @vite('resources/css/about.css')
@endpush

@section('title', 'About Us - EzySkills')

@section('content')
<div class="about-hero">
  <div class="hero-left">
    <h5>About Us</h5>
    <h1>The Platform<br>For The Next<br><span>Billion Learners</span></h1>
    <p>Transforming tech education for the next generation of students & employees</p>
  </div>
  <div class="hero-right">
    <img src="{{ asset('images/about1.png') }}" alt="Group Studying">
    <img src="{{ asset('images/about2.png') }}" alt="Teamwork">
    <img src="{{ asset('images/about3.png') }}" alt="Developers">
  </div>
</div>

<section class="story-section">
  <div class="story-left">
    <img src="{{ asset('images/about4.png') }}" alt="Smiling Students">
  </div>
  <div class="story-right">
    <h5>Our Story</h5>
    <h2>Innovating new<br><span>ways to train</span><br>students</h2>
    <p>
      We see no limits to what we can achieve by harnessing our individual and collective strengths.
      We are changing the world with our ideas, insights, and unique perspectives.
      <br><br>
      Our innovation is led by data, curiosity and the occasional happy accident.
      We create an uplifting environment where we learn from our failures and celebrate our success.
    </p>
  </div>
</section>

<section class="mission-vision">
  <div class="mission-box">
    <img src="{{ asset('images/target-icon.png') }}" alt="Mission Icon">
    <h3><span>Our</span> Mission</h3>
    <p>
      Provide practice-based skill trainings using unique teaching methodologies & skill platforms to
      enhance right skills required in industry for working professionals, non-tech professionals, college students
      & startups through new skilling, upskilling & re-skilling.
    </p>
  </div>
  <div class="vision-box">
    <img src="{{ asset('images/telescope-icon.png') }}" alt="Vision Icon">
    <h3><span>Our</span> Vision</h3>
    <p>
      To transform into a right employee by imparting industry-suited IT skills in a corporate office
      working environment with holistic approach.
    </p>
  </div>
</section>
@endsection
