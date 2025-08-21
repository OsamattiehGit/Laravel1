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
        padding-top: 80px;
        padding-bottom: 100px;
        overflow: hidden;
        position: relative;
        min-height: 600px;
    }

    .hero-label {
        letter-spacing: 2px;
        font-weight: 700;

        color: var(--bs-warning);
        text-transform: uppercase;
        font-size: 3rem;
        margin-bottom: 20px;
    }

    .hero-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 4.5rem;
        line-height: 1.2;
        color: white;
        margin-bottom: 20px;
    }

    .hero-description {
        color: var(--bs-light-blue);
        font-size: 2rem;
        margin-bottom: 0;
        max-width: 600px;
    }

    .hero-text-content {
        padding-left: 60px;
    }

    /* Hero Images Container */
    .hero-images-container {
        position: relative;
        height: 500px;
        width: 100%;
    }

    .hero-img-1 {
        position: absolute;
        top: 10px;
        left: 40%;
        width: 300px;
        height: 220px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 8px 32px rgba(0,0,0,0.15);
        z-index: 3;
    }

    .hero-img-2 {
        position: absolute;
        top: 100px;
        left: 10%;
        width: 200px;
        height: 250px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 8px 32px rgba(0,0,0,0.15);
        z-index: 2;
    }

.about-img-overlap {
    display: block;
    position: absolute;
    left: 80%;
    top: 530px;
    transform: translateX(-50%);
    width: 400px;
    height: 300px;
    border-radius: 18px;
    object-fit: cover;
    box-shadow: 0 12px 48px rgba(0,0,0,0.16);
    z-index: 15;
    background: #fff;
    border: none;
    margin: 0;
}
@media (max-width: 992px) {
    .about-img-overlap {
        width: 320px;
        height: 150px;
        top: 340px;
    }
}
@media (max-width: 768px) {
    .about-img-overlap {
        width: 200px;
        height: 90px;
        top: 240px;
    }
}
@media (max-width: 768px) {
    .hero-images-container {
        position: static !important;
        display: flex !important;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 18px;
        height: auto !important;
        margin-top: 2em;
        margin-bottom: 2em;
    }
    .hero-img-1,
    .hero-img-2,
    .hero-img-3,
    .about-img-overlap {
        position: static !important;
        width: 90vw !important;
        max-width: 360px;
        height: auto !important;
        min-height: 100px;
        margin: 0 auto !important;
        display: block;
        box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        left: unset !important;
        top: unset !important;
        right: unset !important;
        bottom: unset !important;
        transform: none !important;
        z-index: 2;
    }
    /* Hide overlap floating image on mobile if needed */
    .about-img-overlap {
        display: none !important;
    }
}




    /* Story Section */

    .story-text-container {
                       display : block;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: auto;
        position: relative;

        justify-content: center;
        align-items: center;
    }
    .story-section {
        padding: 120px 0 80px 0;
        background: #f8f9fa;
    }

    .story-label {
        letter-spacing: 2px;
        font-weight: 700;
        color: var(--bs-primary);
        text-transform: uppercase;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .story-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 3rem;
        line-height: 1.3;
        color: var(--bs-text-dark);
        margin-bottom: 30px;
    }

    .story-text {
        color: var(--bs-text-muted);
        font-size: 1.3rem;
        line-height: 1.7;
        margin-bottom: 20px;
    }

    .story-image-container {
                       display : block;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: auto;
        position: relative;

        justify-content: center;
        align-items: center;
    }

    .story-main-image {
                       display : block;
        margin-left: auto;
        margin-right: auto;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        object-fit: cover;
        position: relative;
        z-index: 2;
        box-shadow: 0 12px 48px rgba(0,0,0,0.1);
    }

    .story-decoration {
        position: absolute;
        right: 10%;
        top: 60%;
        width: 220px;
        height: 220px;
        z-index: 1;
    }

    .story-pattern {
        position: absolute;
        left: -30px;
        bottom: 50px;
        width: 80px;
        height: 80px;
        z-index: 1;
    }
    @media (max-width: 768px) {
    .story-section .row {
        flex-direction: column !important;
        align-items: center !important;
    }
    .story-image-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        position: static !important;
        width: 100%;
        margin-bottom: 1.5em;
    }
    .story-main-image {
        width: 70vw !important;
        max-width: 260px;
        height: auto !important;
        border-radius: 50%;
        margin: 0 auto;
        position: static !important;
        z-index: 2;
        box-shadow: 0 8px 24px rgba(0,0,0,0.10);
    }
    .story-decoration {
        position: static !important;
        margin-top: -40px; /* Pulls the orange circle up behind image */
        width: 44vw !important;
        max-width: 140px;
        height: auto !important;
        display: block;
        z-index: 1;
    }
    .story-label, .story-title, .story-text {
        text-align: center !important;
    }
}


    /* Mission Vision Section */
    .mission-vision-section {
        background: var(--bs-primary);
        padding: 80px 0;
        color: white;
    }

    .mission-vision-card {
        text-align: center;
        padding: 40px 30px;
    }

    .mission-vision-icon {
        width: 100px;
        height: 100px;
        margin-bottom: 25px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: auto;
        margin-right: auto;
    }

    .mission-vision-icon img {
        width: 120px;
        height: 100px;
    }

    .mission-vision-title {
        font-size: 1.8rem;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .mission-vision-text {
        font-size: 1rem;
        line-height: 1.6;
        opacity: 0.9;
    }

    /* Team and Advisors Sections */
    .team-advisors-section {
        background: white;
        padding: 60px 0;
        margin-bottom: 40px;
    }

    .section-title {
        font-size: 2.5rem;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        color: var(--bs-text-dark);
        text-align: center;
        margin-bottom: 50px;
    }

    .team-container, .advisors-container {

        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .profile-card {
        display : block;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
        margin: 0 15px;
        max-width: 200px;
    }

    .profile-image {
           display : block;
        margin-left: auto;
        margin-right: auto;
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #f5f5f7;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .profile-name {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--bs-text-dark);
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .profile-role {
        font-size: 0.9rem;
        color: var(--bs-text-muted);
        line-height: 1.4;
    }

    .carousel-control {
        background: white;
        color: var(--bs-primary);
        border: 2px solid #e9ecef;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .carousel-control:hover {
        background: var(--bs-warning);
        color: white;
        border-color: var(--bs-warning);
        transform: translateY(-2px);
    }

    .carousel-control svg {
        width: 20px;
        height: 20px;
    }

    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .hero-img-1 {
            right: 80px;
            width: 180px;
            height: 110px;
        }

        .hero-img-2 {
            right: 160px;
            width: 180px;
            height: 110px;
        }

        .hero-img-3 {
            width: 280px;
            height: 180px;
        }
    }

    @media (max-width: 992px) {
        .hero-section {
            padding-bottom: 60px;
        }

        .hero-text-content {
            padding-left: 40px;
        }

        .hero-images-container {
            height: 400px;
            margin-top: 40px;
        }

        .hero-img-1 {
            top: 20px;
            right: 50px;
            width: 160px;
            height: 100px;
        }

        .hero-img-2 {
            top: 50px;
            right: 120px;
            width: 160px;
            height: 100px;
        }

        .hero-img-3 {
            bottom: 20px;
            right: 30px;
            width: 250px;
            height: 160px;
        }

        .story-section {
            padding: 80px 0 60px 0;
        }

        .story-main-image {
               display : block;
        margin-left: auto;
        margin-right: auto;
            width: 250px;
            height: 250px;
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-text-content {
            padding-left: 20px;
            text-align: center;
        }

        .hero-images-container {
            height: 300px;
        }

        .hero-img-1, .hero-img-2 {
            width: 140px;
            height: 90px;
        }

        .hero-img-3 {
            width: 200px;
            height: 130px;
        }

        .story-title {
            font-size: 2.2rem;
        }

        .mission-vision-title {
            font-size: 1.6rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .team-container, .advisors-container {
            flex-direction: column;
            gap: 30px;
        }

        .profile-image {
            width: 120px;
            height: 120px;
        }
    }

    @media (max-width: 576px) {
        .hero-section {
            padding: 40px 0;
            border-radius: 0 0 30px 0;
        }

        .hero-text-content {
            padding-left: 10px;
            text-align: center;
        }

        .hero-title {
            font-size: 2rem;
        }

        .hero-images-container {
            height: 250px;
        }

        .hero-img-1, .hero-img-2 {
            width: 120px;
            height: 80px;
        }

        .hero-img-3 {
            width: 180px;
            height: 120px;
        }

        .story-main-image {
            width: 200px;
            height: 200px;
        }

        .mission-vision-card {
            padding: 30px 20px;
        }
    }
</style>
@endpush

@section('content')

<!-- Hero Section -->
<section class="hero-section" style="position:relative; z-index:2;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-text-content">
                    <div class="hero-label">ABOUT US</div>
                    <h1 class="hero-title">
                        The Platform<br>
                        For The Next<br>
                        Billion Learners
                    </h1>
                    <p class="hero-description">
                        Transforming tech education for the next generation of students & employees
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-images-container">

                    <img src="{{ asset('images/about1.png') }}" class="hero-img-1" alt="Library">
                    <img src="{{ asset('images/about2.png') }}" class="hero-img-2" alt="Team">
                </div>
            </div>
        </div>
    </div>
</section>
<img src="{{ asset('images/about3.png') }}" class="about-img-overlap" alt="Students">
<!-- Story Section -->
<section class="story-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-1">
                <div class="story-image-container">
                    <img src="{{ asset('images/about4.png') }}" alt="Our Story" class="story-main-image">
                    <img src="{{ asset('images/orange-circle.svg') }}" alt="Design" class="story-decoration">
                    <!-- Add pattern dots if you have the asset -->
                    <!-- <div class="story-pattern"></div> -->
                </div>
            </div>
            <div class="col-lg-6 order-lg-2">
                <div class="story-text-container">
                              <div class="col-lg-6 order-lg-2">
                <div class="story-label">OUR STORY</div>
                <h2 class="story-title">
                    <span class="text-warning-custom">
                        Innovating new<br>
                        ways to train<br>
                        students
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
                </div>
            </div>

</section>

<!-- Mission and Vision Section -->
<section class="mission-vision-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="mission-vision-card">
                    <div class="mission-vision-icon">
                        <img src="{{ asset('images/target-icon.png') }}" alt="Mission">
                    </div>
                    <h3 class="mission-vision-title">
                        Our <span class="text-warning-custom">Mission</span>
                    </h3>
                    <p class="mission-vision-text">
                        Provide practice-based skill trainings using a unique teaching methodology & skill platform to enhance
                        right skills required in an industry for working professionals, Non-Tech professionals, College students
                        & Start-ups through new skilling, upskilling & re-skilling.
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mission-vision-card">
                    <div class="mission-vision-icon">
                        <img src="{{ asset('images/telescope-icon.png') }}" alt="Vision">
                    </div>
                    <h3 class="mission-vision-title">
                        Our <span class="text-warning-custom">Vision</span>
                    </h3>
                    <p class="mission-vision-text">
                        To transform into a right employee by imparting industry-suited IT skills in a corporate office
                        working environment with a holistic approach.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="team-advisors-section">
    <div class="container">
        <h2 class="section-title">
            Our <span class="text-warning-custom">Team</span>
        </h2>
        <div class="row align-items-center justify-content-center">
            <div class="col-auto">
                <button class="btn carousel-control" type="button" aria-label="Previous">
                    <svg viewBox="-19.04 0 75.803 75.803" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                        <g transform="translate(-624.082 -383.588)">
                            <path d="M660.313,383.588a1.5,1.5,0,0,1,1.06,2.561l-33.556,33.56a2.528,2.528,0,0,0,0,3.564l33.556,33.558a1.5,1.5,0,0,1-2.121,2.121L625.7,425.394a5.527,5.527,0,0,1,0-7.807l33.556-33.559A1.5,1.5,0,0,1,660.313,383.588Z"></path>
                        </g>
                    </svg>
                </button>
            </div>
            <div class="col">
                <div class="team-container">
                    <div class="profile-card">
                        <img src="{{ asset('images/team1.png') }}" class="profile-image" alt="Kishore Kumar">
                        <div class="profile-name">KISHORE KUMAR</div>
                        <div class="profile-role">CEO & Founder, Caramel IT Services</div>
                    </div>
                    <div class="profile-card">
                        <img src="{{ asset('images/team2.png') }}" class="profile-image" alt="Suchitra">
                        <div class="profile-name">SUCHITRA</div>
                        <div class="profile-role">Director - HR & Operations</div>
                    </div>
                    <div class="profile-card">
                        <img src="{{ asset('images/team3.png') }}" class="profile-image" alt="Naren M">
                        <div class="profile-name">NAREN M</div>
                        <div class="profile-role">Co-Founder</div>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <button class="btn carousel-control" type="button" aria-label="Next">
                    <svg viewBox="-19.04 0 75.804 75.804" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                        <g transform="translate(-831.568 -384.448)">
                            <path d="M833.068,460.252a1.5,1.5,0,0,1-1.061-2.561l33.557-33.56a2.53,2.53,0,0,0,0-3.564l-33.557-33.558a1.5,1.5,0,0,1,2.122-2.121l33.556,33.558a5.53,5.53,0,0,1,0,7.807l-33.557,33.56A1.5,1.5,0,0,1,833.068,460.252Z"></path>
                        </g>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Advisors Section -->
<section class="team-advisors-section">
    <div class="container">
        <h2 class="section-title">
            Our <span class="text-warning-custom">Advisors</span>
        </h2>
        <div class="row align-items-center justify-content-center">
            <div class="col-auto">
                <button class="btn carousel-control" type="button" aria-label="Previous">
                    <svg viewBox="-19.04 0 75.803 75.803" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                        <g transform="translate(-624.082 -383.588)">
                            <path d="M660.313,383.588a1.5,1.5,0,0,1,1.06,2.561l-33.556,33.56a2.528,2.528,0,0,0,0,3.564l33.556,33.558a1.5,1.5,0,0,1-2.121,2.121L625.7,425.394a5.527,5.527,0,0,1,0-7.807l33.556-33.559A1.5,1.5,0,0,1,660.313,383.588Z"></path>
                        </g>
                    </svg>
                </button>
            </div>
            <div class="col">
                <div class="advisors-container">
                    <div class="profile-card">
                        <img src="{{ asset('images/advisor1.png') }}" class="profile-image" alt="Prasad">
                        <div class="profile-name">PRASAD</div>
                        <div class="profile-role">Pharma Industrialist</div>
                    </div>
                    <div class="profile-card">
                        <img src="{{ asset('images/advisor2.png') }}" class="profile-image" alt="Anand Kumar">
                        <div class="profile-name">ANAND KUMAR</div>
                        <div class="profile-role">Angel Investor</div>
                    </div>
                    <div class="profile-card">
                        <img src="{{ asset('images/advisor3.png') }}" class="profile-image" alt="Chitra">
                        <div class="profile-name">CHITRA</div>
                        <div class="profile-role">Sr. Executive Advisor</div>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <button class="btn carousel-control" type="button" aria-label="Next">
                    <svg viewBox="-19.04 0 75.804 75.804" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                        <g transform="translate(-831.568 -384.448)">
                            <path d="M833.068,460.252a1.5,1.5,0,0,1-1.061-2.561l33.557-33.56a2.53,2.53,0,0,0,0-3.564l-33.557-33.558a1.5,1.5,0,0,1,2.122-2.121l33.556,33.558a5.53,5.53,0,0,1,0,7.807l-33.557,33.56A1.5,1.5,0,0,1,833.068,460.252Z"></path>
                        </g>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Optional: Add carousel functionality
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
