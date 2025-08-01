@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('title', 'Home - Ezyskills')

@section('content')

@vite(['resources/css/home.css', 'resources/js/home.js'])
<section class="hero-section">
    <div class="hero-left">
        <h1 class="hero-heading">
            Skill Your Way <br>
            Up To Success <br>
            With Us
        </h1>
        <p class="hero-subheading">
            Get the skills you need for <br> the future of work.
        </p>

        <div class="search-bar">
            <input type="text" placeholder="Search the course you want">
            <button type="submit">Search</button>
        </div>

        <div class="tags">
            <span class="tag orange">Cloud Computing</span>
            <span class="tag gray">Cyber Security</span>
            <span class="tag gray">DevOps</span>
            <span class="tag gray">Data Science</span>
            <span class="tag gray">Software Testing</span>
        </div>
    </div>

    <div class="hero-right">
        <div class="layer-wrapper">
            <img src="{{ asset('images/small-circle-hero.svg') }}" alt="Hero Image" class="circle-hero-small">
<img src="{{ asset('images/hero-girl-circle.svg') }}" alt="Hero Image" class="circle-hero">
        <img src="{{ asset('images/hero-girl.svg') }}" alt="Hero Image" class="hero-img">
<div class="hero-right">



        <!-- Floating Course Cards -->
     <div class="floating-svg-card">
    <img src="{{ asset('images/icon-analyst.svg') }}" alt="Best Seller Course Cards">
</div>
</div>
</section>


<section class="ai-slider-section">
    <div class="container">
        <div class="ai-slider-content">
            <h2>
                <span class="highlight-blue">World’s</span><br />
                <span class="highlight-blue">First <span class="highlight-blue">AI Based</span></span><br />
                <span class="highlight-orange">Online Learning</span><br />
                <span class="highlight-orange">Platform</span>
            </h2>
        </div>

        <div class="ai-slider-carousel">
            <div class="carousel-track">
                <div class="carousel-slide active">
                    <img src="{{ asset('images/slide-course-selector.png') }}" alt="AI Based Course Selector">
                </div>
                <div class="carousel-slide">
                    <img src="{{ asset('images/slide-scenarios.png') }}" alt="AI Based Scenarios">
                </div>
                <div class="carousel-slide">
                    <img src="{{ asset('images/slide-quizzes.png') }}" alt="AI Based Quizzes">
                </div>
                <div class="carousel-slide">
                    <img src="{{ asset('images/slide-gamification.png') }}" alt="AI Based Gamification">
                </div>
            </div>

            <div class="carousel-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>
    </div>
</section>


<section class="who-can-join">
    <div class="container">
       <div class="who-content">
    <div class="who-left">
        <div class="who-header">
            <span class="subheading-orange">WHO CAN JOIN</span>
            <h2>Skill Development<br>Schemes For All</h2>
        </div>
        <div class="who-grid">
            <div class="who-item">
                <span class="who-number">01</span>
                <img src="{{ asset('images/icon-college.svg') }}" alt="College">
                <p>Colleges/Universities</p>
            </div>
            <div class="who-item">
                <span class="who-number">02</span>
                <img src="{{ asset('images/icon-working.svg') }}" alt="Professionals">
                <p>Individuals/Working Professionals</p>
            </div>
            <div class="who-item">
                <span class="who-number">03</span>
                <img src="{{ asset('images/icon-startup.svg') }}" alt="Startups">
                <p>Startups</p>
            </div>
            <div class="who-item">
                <span class="who-number">04</span>
                <img src="{{ asset('images/icon-corporate.svg') }}" alt="Corporates">
                <p>Corporates</p>
            </div>
        </div>
    </div>

    <div class="who-right">
        <img src="{{ asset('images/who-illustration.svg') }}" alt="Learning Illustration">
    </div>
</div>

    </div>
</section>

<section class="how-it-works">
    <div class="how-header">
        <span class="badge">How It Works</span>
    </div>
    <div class="how-content">
        <img src="{{ asset('images/how-it-works.svg') }}" alt="How It Works Steps">
    </div>
</section>



<!-- HOME.BLADE.PHP SECTION -->
<section class="popular-courses">
    <h2><span class="highlight-blue">Popular</span> <span class="highlight-orange">Courses</span></h2>

    <div class="course-grid">
        @foreach ($courses as $course)
        <div class="course-card">
            <div class="course-image">
               <img src="{{ Storage::url($course->image) }}" alt="{{ $course->title }}">

            </div>
            <div class="course-body">
                <h4>{{ $course->title }}</h4>
                <p>{{ Str::limit($course->description, 100) }}</p>

                <div class="button-group">
                    <a href="{{ route('course.show', $course->id) }}" class="btn">Live Demo</a>
                    <a href="{{ route('course.show', $course->id) }}" class="btn btn-secondary">Enroll Now</a>
                </div>

                <a href="#" class="btn btn-full">Download Curriculum</a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="view-all">
        <a href="{{ route('courses') }}" class="btn btn-view-all">View All Courses</a>
    </div>
</section>



<section class="achievements-section">
    <div class="container achievements-container">
        <div class="achievements-left">
            <img src="{{ asset('images/achievements-illustration.svg') }}" alt="Achievements Illustration">
        </div>

        <div class="achievements-right">
            <h3><span class="highlight-blue">Our</span> <span class="highlight-orange">Achievements</span></h3>

            <div class="stats-boxes">
                <div class="stat-box">
                    <img src="{{ asset('images/icons/student.svg') }}" alt="Students Icon" class="stat-icon">
                    <div>
                        <strong>100</strong>
                        <p>Students Trained</p>
                    </div>
                </div>

                <div class="stat-box">
                    <img src="{{ asset('images/icons/book.svg') }}" alt="Courses Icon" class="stat-icon">
                    <div>
                        <strong>50</strong>
                        <p>Courses Available</p>
                    </div>
                </div>

                <div class="stat-box wide">
                    <strong class="blue-text">70%</strong>
                    <p>Students Secured Jobs in Level 1 Companies</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="mentors-section">
  <div class="container">
    <h2 class="mentors-title">
      Meet Our Professional
      <span class="highlight-orange">Mentors & Trainers</span>
    </h2>
    <div class="mentors-cards">
      {{-- Card 1 --}}
      <div class="mentor-card mentor-card--best">
        <div class="mentor-badge">🏆 Best Trainer</div>
        <img src="{{ asset('images/icons/male1.svg') }}" alt="Sandeep" class="mentor-photo">
        <h3 class="mentor-name">Sandeep</h3>
        <p class="mentor-role">.Net & Azure</p>
        <div class="mentor-rating">⭐️⭐️⭐️⭐️☆ <span>72 Reviews</span></div>
        <div class="mentor-meta">
          <span class="mentor-meta--item">📚 39 Modules</span>
          <span class="mentor-meta--item">👨‍🎓 375 Students</span>
        </div>
        <p class="mentor-desc">
          Sandeep is a Software Developer who expertised in .NET & Azure for more than 24 years and training 100’s of students to accomplish their goals & dreams.
        </p>
      </div>

      {{-- Card 2 --}}
      <div class="mentor-card">
        <img src="{{ asset('images/icons/male2.svg') }}" alt="Sudhansu" class="mentor-photo">
        <h3 class="mentor-name">Sudhansu</h3>
        <p class="mentor-role">Cloud & Cyber Security, Forensic</p>
        <div class="mentor-rating">⭐️⭐️⭐️⭐️☆ <span>38 Reviews</span></div>
        <div class="mentor-meta">
          <span class="mentor-meta--item">📚 27 Modules</span>
          <span class="mentor-meta--item">👨‍🎓 169 Students</span>
        </div>
        <p class="mentor-desc">
          Sudhansu is a Software Developer who expertised in Cloud security, Data Center & Forensic for more than 22 years and training 100’s of students to accomplish their goals & dreams.
        </p>
      </div>

      {{-- Card 3 --}}
      <div class="mentor-card">
        <img src="{{ asset('images/icons/female1.svg') }}" alt="Ruchika Tuteja" class="mentor-photo">
        <h3 class="mentor-name">Ruchika Tuteja</h3>
        <p class="mentor-role">UIUX Trainer</p>
        <div class="mentor-rating">⭐️⭐️⭐️⭐️☆ <span>65 Reviews</span></div>
        <div class="mentor-meta">
          <span class="mentor-meta--item">📚 44 Modules</span>
          <span class="mentor-meta--item">👨‍🎓 212 Students</span>
        </div>
        <p class="mentor-desc">
          I have 9 years of experience in Fullstack development. Have worked on multiple projects on. I can provide real-time simulation of these various development languages and framework by means of multiple projects. Can provide guidance…
        </p>
      </div>
    </div>
  </div>
</section>






<section class="certifications-section">
  <div class="container">
    <h2 class="section-title">
      Our <span class="highlight-orange">Certifications</span>
    </h2>
    <div class="cert-logos">
      <img src="{{ asset('images/icons/iso27001.svg') }}" alt="ISO 27001 Certified">
      <img src="{{ asset('images/icons/iso9001.svg') }}"  alt="ISO 9001 Certified">
      <img src="{{ asset('images/icons/iso20000.svg') }}" alt="ISO 20000-1 Certified">
      <img src="{{ asset('images/icons/iso29993.svg') }}" alt="ISO 29993 Certified">
    </div>
  </div>
</section>

<section class="below‐collab‐section">
     <h2 class="section-title">
      Our <span class="highlight-orange">Collaborations</span>
    </h2>
  <img
    src="{{ asset('images/icons/collabs.svg') }}"
    alt="Collaborations and what comes next"
    class="below‐collab‐svg"
  />
</section>


@endsection
