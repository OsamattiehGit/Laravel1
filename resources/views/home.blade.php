<!DOCTYPE html>
<html lang="en">
    @extends('layouts.app')
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Ezyskills</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
@vite ([
        'resources/css/home.css'])
    @stack('styles')
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-heading">
                        Skill Your Way <br>
                        Up To Success <br>
                        With Us
                    </h1>
                    <p class="hero-subheading">
                        Get the skills you need for <br> the future of work.
                    </p>

                    <div class="search-container">
                   <form action="{{ route('courses') }}" method="GET" class="d-flex align-items-center gap-2 mb-4">
    <input
        type="text"
        name="q"
        class="form-control search-input"
        placeholder="Search The Course Here…"
        value="{{ old('q', $q ?? request('q')) }}"
        style="font-size: 1.25rem; border: 2px solid #FF8C00; border-radius: 10px;width:400px"
    >
    <button class="search-btn" type="submit" style="background: #002C6B; border: none;">Search</button>
</form>


                    </div>

                    <div class="tags-container">
                        <span class="tag orange">Cloud Computing</span>
                        <span class="tag gray">Cyber Security</span>
                        <span class="tag gray">DevOps</span>
                        <span class="tag gray">Data Science</span>
                        <span class="tag gray">Software Testing</span>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-right">
                        <div class="layer-wrapper">
                                        <img src="{{ asset('images/small-circle-hero.svg') }}" alt="Hero Image" class="circle-hero-small">
<img src="{{ asset('images/hero-girl-circle.svg') }}" alt="Hero Image" class="circle-hero">
        <img src="{{ asset('images/hero-girl.svg') }}" alt="Hero Image" class="hero-img">
                            <div class="floating-svg-card">
                                  <img src="{{ asset('images/icon-analyst.svg') }}" alt="Best Seller Course Cards">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Slider Section -->
    <section class="ai-slider-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <h2 class="ai-title">
                        <span class="highlight-blue">World's</span><br>
                        <span class="highlight-blue">First AI Based</span><br>
                        <span class="highlight-orange">Online Learning</span><br>
                        <span class="highlight-orange">Platform</span>
                    </h2>
                </div>
                <div class="col-lg-7">
                    <div id="aiCarousel" class="carousel slide carousel-container" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('images/slide-course-selector.png') }}"  class="d-block w-100" alt="AI Based Course Selector">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/slide-scenarios.png') }}"  class="d-block w-100" alt="AI Based Scenarios">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/slide-quizzes.png') }}" class="d-block w-100" alt="AI Based Quizzes">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/slide-gamification.png') }}" class="d-block w-100" alt="AI Based Gamification">
                            </div>
                        </div>
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#aiCarousel" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#aiCarousel" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#aiCarousel" data-bs-slide-to="2"></button>
                            <button type="button" data-bs-target="#aiCarousel" data-bs-slide-to="3"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Who Can Join Section -->
    <section class="who-can-join">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <span class="subheading-orange">WHO CAN JOIN</span>
                    <h2 class="who-title">Skill Development<br>Schemes For All</h2>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="who-item">
                                <div class="who-number">01</div>
                              <img src="{{ asset('images/icon-college.svg') }}" alt="College">

                                <p>Colleges/Universities</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="who-item">
                                <div class="who-number">02</div>
                               <img src="{{ asset('images/icon-working.svg') }}" alt="Professionals">
                                <p>Individuals/Working Professionals</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="who-item">
                                <div class="who-number">03</div>
                                <img src="{{ asset('images/icon-startup.svg') }}" alt="Startups">
                                <p>Startups</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="who-item">
                                <div class="who-number">04</div>
                               <img src="{{ asset('images/corporate-icon.svg') }}" alt="Corporates">
                                <p>Corporates</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center justify-content-center">
                   <img src="{{ asset('images/who-illustration.svg') }}" alt="Learning Illustration" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works">
        <div class="container text-center">
            <div class="mb-4">
                <span class="badge-custom">How It Works</span>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                      <img src="{{ asset('images/how-it-works.svg') }}" alt="How It Works Steps" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

   <!-- Popular Courses Section -->
    <section class="popular-courses">
       <div class="row course-grid">
    @foreach ($courses as $course)
    <div class="col-lg-3 col-md-6 mb-4 d-flex">
        <div class="course-card w-100 d-flex flex-column">
            <div class="card-top position-relative">
                <img src="{{ Storage::url($course->image) }}" alt="{{ $course->title }}">
                <div class="shelf"></div>
            </div>
            <div class="card-bottom flex-fill d-flex flex-column">
                <h4>{{ $course->title }}</h4>
                <p>{{ Str::limit($course->description, 100) }}</p>
                <div class="button-group mb-2">
                    <a href="{{ route('course.show', $course->id) }}" class="btn btn-outline-custom">Live Demo</a>
                    <a href="{{ route('course.show', $course->id) }}" class="btn btn-solid-custom">Enroll Now</a>
                </div>
                <a href="#" class="btn btn-download w-100 mt-auto">
                    <svg width="30" height="50" viewBox="0 0 59 60" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M26.5029 6.60362C20.0446 6.60362 14.8093 11.839 14.8093 18.2972C14.8093 18.392 14.8107 18.4909 14.8132 18.5961C14.845 19.9555 13.9352 21.1574 12.6182 21.4958C8.83325 22.4684 6.03916 25.9071 6.03916 29.9907C6.03916 34.8345 9.96577 38.7609 14.8093 38.7609H17.7327C19.3473 38.7609 20.6561 40.0697 20.6561 41.6843C20.6561 43.2989 19.3473 44.6077 17.7327 44.6077H14.8093C6.73671 44.6077 0.192383 38.0634 0.192383 29.9907C0.192383 23.9611 3.84136 18.7896 9.04809 16.554C9.92306 7.68512 17.4037 0.756836 26.5029 0.756836C33.0033 0.756836 38.6727 4.29221 41.7016 9.53648C51.1199 9.8434 58.6602 17.5748 58.6602 27.0673C58.6602 36.7546 50.8074 44.6077 41.1198 44.6077C39.5053 44.6077 38.1965 43.2989 38.1965 41.6843C38.1965 40.0697 39.5053 38.7609 41.1198 38.7609C47.5782 38.7609 52.8134 33.5254 52.8134 27.0673C52.8134 20.6091 47.5782 15.3738 41.1198 15.3738C40.7962 15.3738 40.4767 15.3869 40.1604 15.4124C38.9124 15.5134 37.7386 14.8084 37.2417 13.6592C35.4438 9.50286 31.3087 6.60362 26.5029 6.60362ZM29.4263 24.144C31.0409 24.144 32.3497 25.4528 32.3497 27.0673V49.2436L33.2059 48.3873C34.3475 47.2458 36.1986 47.2458 37.3402 48.3873C38.4818 49.5289 38.4818 51.38 37.3402 52.5216L31.4934 58.3684C30.9453 58.9165 30.2016 59.2246 29.4263 59.2246C28.651 59.2246 27.9073 58.9165 27.3592 58.3684L21.5123 52.5216C20.3707 51.38 20.3707 49.5289 21.5123 48.3873C22.654 47.2458 24.505 47.2458 25.6466 48.3873L26.5029 49.2436V27.0673C26.5029 25.4528 27.8117 24.144 29.4263 24.144Z" fill="white"/>
</svg>Download Curriculum
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="view-all-wrapper">
    <a href="{{ route('courses') }}" class="btn btn-view-all">View All Courses</a>
</div>

    </section>

    <!-- Achievements Section -->
    <section class="achievements-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                   <img src="{{ asset('images/achievements-illustration.svg') }}" alt="Achievements Illustration" class="img-fluid">
                </div>
                <div class="col-lg-6">
                    <h3 class="achievements-title"><span class="highlight-blue">Our</span> <span class="highlight-orange">Achievements</span></h3>

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
                        <strong>70%</strong>
                        <p>Students Secured Jobs in Level 1 Companies</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mentors Section -->
    <section class="mentors-section">
        <div class="container">
            <h2 class="mentors-title">
                Meet Our Professional
                <span class="highlight-orange">Mentors & Trainers</span>
            </h2>

            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="mentor-card">
                        <div class="mentor-badge">🏆 Best Trainer</div>
                       <img src="{{ asset('images/icons/male1.svg') }}" alt="Sandeep" class="mentor-photo">

                        <h3 class="mentor-name">Sandeep</h3>
                        <p class="mentor-role">.Net & Azure</p>
                        <div class="mentor-rating">⭐⭐⭐⭐☆ <span style="color: #FF7500;">72 Reviews</span></div>
                        <div class="mentor-meta">
                            <span>📚 39 Modules</span>
                            <span>👨‍🎓 375 Students</span>
                        </div>
                        <p class="mentor-desc">
                            Sandeep is a Software Developer who expertised in .NET & Azure for more than 24 years and training 100's of students to accomplish their goals & dreams.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="mentor-card">
                     <img src="{{ asset('images/icons/male2.svg') }}" alt="Sudhansu" class="mentor-photo">
                        <h3 class="mentor-name">Sudhansu</h3>
                        <p class="mentor-role">Cloud & Cyber Security, Forensic</p>
                        <div class="mentor-rating">⭐⭐⭐⭐☆ <span style="color: #FF7500
                                                    <span>42 Reviews</span></div>
                        <div class="mentor-meta">
                            <span>📚 28 Modules</span>
                            <span>👨‍🎓 250 Students</span>
                        </div>
                        <p class="mentor-desc">
                            Sudhansu is a Software Developer with experience in Cloud & Cyber Security, Forensics, and has helped many students reach their goals.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mx-md-auto">
                    <div class="mentor-card">
                          <img src="{{ asset('images/icons/female1.svg') }}" alt="Ruchika Tuteja" class="mentor-photo">

                        <h3 class="mentor-name">Ruchika Tuteja</h3>
                        <p class="mentor-role">UI/UX Trainer</p>
                        <div class="mentor-rating">⭐⭐⭐⭐☆ <span style="color: #FF7500;">56 Reviews</span></div>
                        <div class="mentor-meta">
                            <span>📚 18 Modules</span>
                            <span>👨‍🎓 259 Students</span>
                        </div>
                        <p class="mentor-desc">
                            Ruchika gives 4 years of experience in Product development, User needs understanding, and guiding design thinking for UI/UX training.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Certifications Section -->
    <section class="certifications-section">
        <div class="container text-center">
            <h3 class="achievements-title"><span class="highlight-orange">Our Certifications</span></h3>
            <div class="cert-logos">
                <img src="{{ asset('images/icons/iso27001.svg') }}" alt="ISO 27001 Certified">
                <img src="{{ asset('images/icons/iso9001.svg') }}"  alt="ISO 9001 Certified">
                <img src="{{ asset('images/icons/iso20000.svg') }}" alt="ISO 20000-1 Certified">
                <img src="{{ asset('images/icons/iso29993.svg') }}" alt="ISO 29993 Certified">
            </div>
        </div>
    </section>

    <!-- Collaborations Section -->
    <section class="collaborations-section">
        <div class="container">
            <h4 class="achievements-title"><span class="highlight-orange">Our Collaborations</span></h4>
            <div class="d-flex justify-content-center align-items-center flex-wrap">
                  <img
    src="{{ asset('images/icons/collabs.svg') }}"
    alt="Collaborations and what comes next"
    class="below‐collab‐svg"
  />
            </div>
        </div>
    </section>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection
