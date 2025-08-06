@extends('layouts.app')
@section('title', 'About Us')

@push('styles')
<style>
    /* Custom Bootstrap theme colors and components */
    :root {
        --bs-primary: #003366;
        --bs-warning: #ff942f;
        --bs-light-blue: #e3e9ef;
        --bs-text-muted: #555b6a;
        --bs-text-dark: #232c41;
    }

    .bg-primary-custom {
        background-color: var(--bs-primary) !important;
    }

    .text-warning-custom {
        color: var(--bs-warning) !important;
    }

    .text-light-blue {
        color: var(--bs-light-blue) !important;
    }

    /* Hero Section */
    .hero-section {
        background: var(--bs-primary);
        border-radius: 0 0 60px 0;
        padding-top: 60px;
        padding-bottom: 120px;
        overflow: visible;
        position: relative;
    }

    .hero-label {
        letter-spacing: 2px;
        font-weight: 700;
        color: var(--bs-warning);
        text-transform: uppercase;
        font-size: 2.3rem;
    }

    .hero-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 4rem;
        line-height: 1.2;
        color: white;
    }

    .hero-description {
        color: var(--bs-light-blue);
        font-size: 1.4rem;
        margin-bottom: 0;
    }

    .hero-img-small {
        width: 280px;
        height: 150px;
        border-radius: 16px;
        object-fit: cover;
        box-shadow: 0 4px 24px 0 rgba(44,62,80,0.10);
    }

    .hero-img-main {
        border-radius: 18px;
        width: 450px;
        box-shadow: 0 10px 36px 0 rgba(44,62,80,0.11);
        position: absolute;
        bottom: -300px;
        right: 20;
        z-index: 3;
    }

    .hero-images-container {
        position: relative;
        height: 300px;
    }

    .hero-img-top {
        width : 250px;
        position: absolute;
        top: 0;
        width: 250px;

    }
      .hero-img-top-2 {
        height:240px;
        width : 110px;
        left: -190px;
        position: absolute;
        top: -30px;
        width: 250px;

    }

    .hero-img-top:first-child {
         width: 350px;
         height: 300px;
        right: 60px;
        top: -60px;
    }

    .hero-img-top:last-child {
        right: 160px;
        top: 30px;
    }

    /* Story Section */
    .story-section {
        padding: 150px 0 80px 0;
    }

    .story-label {
        letter-spacing: 2px;
        font-weight: 700;
        color: var(--bs-primary);
        text-transform: uppercase;
        font-size: 1.8rem;
    }

    .story-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 3rem;
        line-height: 1.3;
        color: var(--bs-text-dark);
    }

    .story-text {
        color: var(--bs-text-dark);
        font-size: 1.04rem;
        line-height: 1.6;
    }

    .story-image-container {
        position: relative;
    }

    .story-main-image {
        width: 250px;
        height: 250px;
        border-radius: 50%;
        object-fit: cover;
        position: relative;
        z-index: 2;
    }

    .story-decoration {
        position: absolute;
        left: 100px;
        top: 100px;
        width: 200px;
        z-index: 1;
    }

    /* Mission Vision Section */
    .mission-vision-section {
        background: var(--bs-primary);
        border-radius: 0 0 0 80px;
        padding: 60px 0 40px 0;
        color: white;
    }

    .mission-vision-icon {
        height: 120px;
        margin-bottom: 1rem;
    }

    .mission-vision-title {
        font-size: 2.1rem;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    /* Team and Advisors Sections */
    .team-advisors-section {
        background: white;
        border-radius: 36px;
        padding: 32px 0 12px 0;
        margin-bottom: 2.8rem;
        box-shadow: 0 2px 20px rgba(0,0,0,0.05);
    }

    .section-title {
        font-size: 2.1rem;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        color: var(--bs-text-dark);
    }

    .profile-image {
           display: block;
    margin-left: auto;
    margin-right: auto;
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 6px solid #f5f5f7;
        box-shadow: 0 2px 16px 0 rgba(44,62,80,0.10);
    }

    .profile-name {
        width: 200px;
    text-align: center;
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--bs-text-dark);
        margin-top: 1rem;
        margin-bottom: 0.25rem;
    }

    .profile-role {
        font-size: 0.95rem;
        color: var(--bs-text-muted);
    }

    .carousel-control {
        background: white;
        color: var(--bs-primary);
        border: 2px solid #dbe3ef;
        border-radius: 50%;
        width: 38px;
        height: 38px;
        font-size: 1.5rem;
        transition: all 0.18s;
    }

    .carousel-control:hover {
        background: var(--bs-warning);
        color: white;
        border-color: var(--bs-warning);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.2rem;
        }

        .story-title {
            font-size: 1.8rem;
        }

        .mission-vision-title {
            font-size: 1.8rem;
        }

        .section-title {
            font-size: 1.8rem;
        }
    }
</style>
@endpush

@section('content')

<!-- Hero Section -->
<section class="hero-section">
    <div class="container pb-5">
        <div class="row g-4 align-items-center">
            <div class="col-lg-7">
                <div class="hero-label mb-3">About Us</div>
                <h1 class="hero-title mb-3">
                    The Platform<br>
                    For The Next<br>
                    Billion Learners
                </h1>
                <p class="hero-description">
                    Transforming tech education for the next generation of students & employees
                </p>
            </div>
            <div class="col-lg-5">
                <div class="hero-images-container">
                    <img src="{{ asset('images/about1.png') }}" class="hero-img-small hero-img-top" alt="Library">
                    <img src="{{ asset('images/about2.png') }}" class="hero-img-small hero-img-top-2" alt="Team">
                    <img src="{{ asset('images/about3.png') }}" class="hero-img-main img-fluid" alt="Students">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Story Section -->
<section class="story-section">
  <div class="container">
    <div class="row g-5 align-items-center">
      {{-- Image on left on lg+, stacks above on sm --}}
      <div class="col-lg-6 order-lg-1">
        <div class="d-flex justify-content-center">
          <div class="story-image-container">
            <img src="{{ asset('images/about4.png') }}" alt="Our Story" class="story-main-image">
            <img src="{{ asset('images/small-circle-hero.svg') }}" alt="Design" class="story-decoration">
          </div>
        </div>
      </div>

      {{-- Text on right on lg+, stacks below on sm --}}
      <div class="col-lg-6 order-lg-2">
        <div class="story-label mb-3">Our Story</div>
        <h2 class="story-title mb-4">
          <span class="text-warning-custom">
            Innovating new<br>
            ways to train students
          </span>
        </h2>
        <p class="story-text">
          We see no limits to what we can achieve by harnessing our individual and collective strengths.
          We are changing the world with our ideas, insights, and unique perspectives.
        </p>
        <p class="story-text">
          Our innovation is led by data, curiosity and the occasional happy accident. We create an uplifting
          environment where we learn from our failures and celebrate our success.
        </p>
      </div>
    </div>
  </div>
</section>
<!-- Mission and Vision Section -->
<section class="mission-vision-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6 text-center">
                <div class="d-flex justify-content-center mb-3">
                    <img src="{{ asset('images/target-icon.png') }}" class="mission-vision-icon" alt="Mission">
                </div>
                <h3 class="mission-vision-title">
                    Our <span class="text-warning-custom">Mission</span>
                </h3>
                <p class="mb-0">
                    Provide practice-based skill trainings using a unique teaching methodology & skill platform to enhance
                    right skills required in an industry for working professionals, Non-Tech professionals, College students
                    & Start-ups through new skilling, upskilling & re-skilling.
                </p>
            </div>
            <div class="col-lg-6 text-center">
                <div class="d-flex justify-content-center mb-3">
                    <img src="{{ asset('images/telescope-icon.png') }}" class="mission-vision-icon" alt="Vision">
                </div>
                <h3 class="mission-vision-title">
                    Our <span class="text-warning-custom">Vision</span>
                </h3>
                <p class="mb-0">
                    To transform into a right employee by imparting industry-suited IT skills in a corporate office
                    working environment with a holistic approach.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<!-- Team Section -->
<section class="team-advisors-section">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            Our <span class="text-warning-custom">Team</span>
        </h2>
        <div class="row align-items-center justify-content-center">
            <div class="col-auto">
                <button class="btn carousel-control d-flex align-items-center justify-content-center"
                        type="button" aria-label="Previous">
                        <svg viewBox="-19.04 0 75.803 75.803" xmlns="http://www.w3.org/2000/svg" fill="#ff942f" stroke="#ff942f"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Group_64" data-name="Group 64" transform="translate(-624.082 -383.588)"> <path id="Path_56" data-name="Path 56" d="M660.313,383.588a1.5,1.5,0,0,1,1.06,2.561l-33.556,33.56a2.528,2.528,0,0,0,0,3.564l33.556,33.558a1.5,1.5,0,0,1-2.121,2.121L625.7,425.394a5.527,5.527,0,0,1,0-7.807l33.556-33.559A1.5,1.5,0,0,1,660.313,383.588Z" fill="#0c2c67"></path> </g> </g></svg>
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            <!-- Put each profile in col with flex column and align center -->
            <div class="col d-flex justify-content-center">
                <div class="d-flex flex-row flex-wrap justify-content-center gap-5 w-100">
                    <div class="d-flex flex-column align-items-center mx-4">
                        <img src="{{ asset('images/team1.png') }}" class="profile-image mb-3" alt="Kishore Kumar">
                        <div class="profile-name text-center">KISHORE KUMAR</div>
                        <div class="profile-role text-center">CEO & Founder, Caramel IT Services</div>
                    </div>
                    <div class="d-flex flex-column align-items-center mx-4">
                        <img src="{{ asset('images/team2.png') }}" class="profile-image mb-3" alt="Suchitra">
                        <div class="profile-name text-center">SUCHITRA</div>
                        <div class="profile-role text-center">Director - HR & Operations</div>
                    </div>
                    <div class="d-flex flex-column align-items-center mx-4">
                        <img src="{{ asset('images/team3.png') }}" class="profile-image mb-3" alt="Naren M">
                        <div class="profile-name text-center">NAREN M</div>
                        <div class="profile-role text-center">Co-Founder</div>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <button class="btn carousel-control d-flex align-items-center justify-content-center"
                        type="button" aria-label="Next">
                        <svg viewBox="-19.04 0 75.804 75.804" xmlns="http://www.w3.org/2000/svg" fill="#ff942f" stroke="#ff942f"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Group_65" data-name="Group 65" transform="translate(-831.568 -384.448)"> <path id="Path_57" data-name="Path 57" d="M833.068,460.252a1.5,1.5,0,0,1-1.061-2.561l33.557-33.56a2.53,2.53,0,0,0,0-3.564l-33.557-33.558a1.5,1.5,0,0,1,2.122-2.121l33.556,33.558a5.53,5.53,0,0,1,0,7.807l-33.557,33.56A1.5,1.5,0,0,1,833.068,460.252Z" fill="#0c2c67"></path> </g> </g></svg>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Advisors Section -->
<section class="team-advisors-section">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            Our <span class="text-warning-custom">Advisors</span>
        </h2>
        <div class="row align-items-center justify-content-center">
            <div class="col-auto">
                <button class="btn carousel-control d-flex align-items-center justify-content-center"
                        type="button" aria-label="Previous">
                        <svg viewBox="-19.04 0 75.803 75.803" xmlns="http://www.w3.org/2000/svg" fill="#ff942f" stroke="#ff942f"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Group_64" data-name="Group 64" transform="translate(-624.082 -383.588)"> <path id="Path_56" data-name="Path 56" d="M660.313,383.588a1.5,1.5,0,0,1,1.06,2.561l-33.556,33.56a2.528,2.528,0,0,0,0,3.564l33.556,33.558a1.5,1.5,0,0,1-2.121,2.121L625.7,425.394a5.527,5.527,0,0,1,0-7.807l33.556-33.559A1.5,1.5,0,0,1,660.313,383.588Z" fill="#0c2c67"></path> </g> </g></svg>
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            <div class="col d-flex justify-content-center">
                <div class="d-flex flex-row flex-wrap justify-content-center gap-5 w-100">
                    <div class="d-flex flex-column align-items-center mx-4">
                        <img src="{{ asset('images/advisor1.png') }}" class="profile-image mb-3" alt="Prasad">
                        <div class="profile-name text-center">PRASAD</div>
                        <div class="profile-role text-center">Pharma Industrialist</div>
                    </div>
                    <div class="d-flex flex-column align-items-center mx-4">
                        <img src="{{ asset('images/advisor2.png') }}" class="profile-image mb-3" alt="Anand Kumar">
                        <div class="profile-name text-center">ANAND KUMAR</div>
                        <div class="profile-role text-center">Angel Investor</div>
                    </div>
                    <div class="d-flex flex-column align-items-center mx-4">
                        <img src="{{ asset('images/advisor3.png') }}" class="profile-image mb-3" alt="Chitra">
                        <div class="profile-name text-center">CHITRA</div>
                        <div class="profile-role text-center">Sr. Executive Advisor</div>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <button class="btn carousel-control d-flex align-items-center justify-content-center"
                        type="button" aria-label="Next">
                        <svg viewBox="-19.04 0 75.804 75.804" xmlns="http://www.w3.org/2000/svg" fill="#ff942f" stroke="#ff942f"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Group_65" data-name="Group 65" transform="translate(-831.568 -384.448)"> <path id="Path_57" data-name="Path 57" d="M833.068,460.252a1.5,1.5,0,0,1-1.061-2.561l33.557-33.56a2.53,2.53,0,0,0,0-3.564l-33.557-33.558a1.5,1.5,0,0,1,2.122-2.121l33.556,33.558a5.53,5.53,0,0,1,0,7.807l-33.557,33.56A1.5,1.5,0,0,1,833.068,460.252Z" fill="#0c2c67"></path> </g> </g></svg>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Optional: Add carousel functionality
document.addEventListener('DOMContentLoaded', function() {
    // You can add carousel navigation logic here if needed
    const carouselButtons = document.querySelectorAll('.carousel-control');

    carouselButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Add your carousel logic here
            console.log('Carousel button clicked');
        });
    });
});
</script>
@endpush
