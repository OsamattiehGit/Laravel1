{{-- resources/views/course.blade.php --}}
@extends('layouts.app')

@push('styles')
  @vite('resources/css/course.css')
@endpush
@vite('resources/js/course.js')
@section('content')

  {{-- ────────────── Banner ────────────── --}}
  <div class="course-banner">
    <div class="container text-center">
      @if($course->image)
        <img src="{{ Storage::url($course->image) }}"
             alt="{{ $course->title }}"
             class="banner-image">
      @endif
      <h1 class="banner-title">{{ $course->title }}</h1>
      <h2 class="banner-subtitle">
        {{ $course->subtitle ?? 'Basic to Advance Level Coding' }}
      </h2>
    </div>
  </div>

  {{-- ────────────── Main Content ────────────── --}}
  <div class="container course-main">

    <div class="row">
      {{-- Left: About + Objectives --}}
      <div class="col-lg-6">
        <h2 class="section-title">About The Course</h2>
        <p class="about-text">{{ $course->description }}</p>

        @if(!empty($course->objectives))
          <h3 class="section-subtitle">Objectives</h3>
          <ul class="objectives-list">
            @foreach($course->objectives as $obj)
              <li>
                <span class="checkmark">✔︎</span>
                <span class="objective-text">{{ $obj }}</span>
              </li>
            @endforeach
          </ul>
        @endif
      </div>

      {{-- Right: Accordion Course Content --}}
      <div class="col-lg-6">
        <h2 class="section-title">Course Content</h2>
        @if(!empty($course->course_content))
          <div class="accordion">
            @foreach($course->course_content as $i => $module)
              <div class="accordion-item">
                <input type="checkbox"
                       id="mod-{{ $i }}"
                       class="accordion-toggle">
                <label for="mod-{{ $i }}"
                       class="accordion-header">
                  <span class="mod-index">
                    {{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}
                  </span>
                  <span class="mod-title">{{ $module }}</span>
                  <span class="mod-icon">＋</span>
                </label>
                <div class="accordion-body">
                  {{-- Optional expanded details go here --}}
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    {{-- ────────────── Projects Grid ────────────── --}}
    @if(!empty($course->projects))
    <hr class="section-divider">
      <h2 class="section-title mt-5">
        {{ $course->title }} Projects
      </h2>
      <div class="projects-grid">
        @foreach($course->projects as $proj)
          <div class="project-card">
            <img src="{{ Storage::url($proj['icon']) }}"
                 alt="{{ $proj['title'] }}"
                 class="project-icon">
            <h4 class="project-title">{{ $proj['title'] }}</h4>
            <p class="project-subtitle">
              {{ $proj['subtitle'] }}
            </p>
          </div>
        @endforeach
      </div>
    @endif

    {{-- ────────────── Call-to-Action Banner ────────────── --}}
<div class="cta-banner">
  <div class="cta-text">
    Wanna check more about the course?
  </div>

  {{-- Top right outline buttons --}}
  <div class="cta-buttons">
    <a href="#" class="cta-btn cta-outline">
      {{-- Monitor icon SVG --}}
   <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M0.393066 2.47978C0.393066 1.49664 1.19006 0.699646 2.1732 0.699646H16.4142C17.3974 0.699646 18.1944 1.49664 18.1944 2.47978V12.2705C18.1944 13.2537 17.3974 14.0506 16.4142 14.0506H10.1838V15.8308H12.854C13.3456 15.8308 13.7441 16.2292 13.7441 16.7208C13.7441 17.2124 13.3456 17.6109 12.854 17.6109H5.73346C5.24189 17.6109 4.84339 17.2124 4.84339 16.7208C4.84339 16.2292 5.24189 15.8308 5.73346 15.8308H8.40366V14.0506H2.1732C1.19006 14.0506 0.393066 13.2537 0.393066 12.2705V2.47978ZM16.4142 12.2705V2.47978H2.1732V12.2705H16.4142Z" fill="#F98149"/>
</svg>

      <span>Demo</span>
    </a>

 <button id="enroll-btn" class="cta-btn cta-outline"
        data-course-id="{{ $course->id }}"
        data-course-title="{{ $course->title }}">
    {{-- Cursor-click icon SVG --}}
        <svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M2.32675 6.15315V1.43144C1.92263 1.43144 1.59502 1.11111 1.59502 0.715964C1.59502 0.320821 1.92263 0.000488281 2.32675 0.000488281H11.1076C11.5117 0.000488281 11.8393 0.320821 11.8393 0.715964C11.8393 1.11111 11.5117 1.43144 11.1076 1.43144V6.15315C11.7894 6.88344 12.1555 7.60893 12.3506 8.18109C12.4574 8.49454 12.5125 8.76042 12.5408 8.95452C12.555 9.05169 12.5626 9.13103 12.5666 9.18977C12.5686 9.21918 12.5697 9.24343 12.5703 9.26218L12.5708 9.27892L12.5709 9.28615L12.5711 9.29502V9.2986V9.30017V9.30096C12.5711 9.30096 12.5677 9.20702 12.5711 9.30167C12.5711 9.69683 12.2435 10.0171 11.8393 10.0171H7.44891V13.5945C7.44891 13.9897 7.12131 14.31 6.71717 14.31C6.31304 14.31 5.98544 13.9897 5.98544 13.5945V10.0171H1.59502C1.19089 10.0171 0.863281 9.69683 0.863281 9.30167C0.863281 8.94393 0.863281 9.30096 0.863281 9.30096V9.30017L0.863289 9.2986L0.863311 9.29502L0.86342 9.28615C0.863523 9.27956 0.863713 9.27155 0.86402 9.26218C0.864635 9.24343 0.865755 9.21918 0.86776 9.18977C0.871762 9.13103 0.879313 9.05169 0.893502 8.95452C0.921864 8.76042 0.976905 8.49454 1.08377 8.18109C1.27882 7.60893 1.64495 6.88344 2.32675 6.15315ZM9.64412 1.43144H3.79023V6.43977C3.79023 6.62951 3.71313 6.81153 3.57591 6.94568C2.95611 7.55169 2.64562 8.14647 2.48867 8.5862H10.9457C10.7887 8.14647 10.4782 7.55169 9.85845 6.94568C9.72124 6.81153 9.64412 6.62951 9.64412 6.43977V1.43144Z" fill="#F98149"/>
</svg>
    <span>Enroll Now</span>
</button>
<!-- Enroll Now Custom Modal -->
<div id="enroll-modal" class="enroll-modal-backdrop" style="display: none;">
    <div class="enroll-modal-content">
        <div class="enroll-modal-header">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#FF8C00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="enroll-modal-icon">
                <rect x="2" y="4" width="20" height="16" rx="3" ry="3"></rect>
                <path d="M8 2v4"></path><path d="M16 2v4"></path>
            </svg>
            <h5>Confirm Enrollment</h5>
        </div>
        <div class="enroll-modal-body">
            <span id="enroll-modal-message">Are you sure you want to enroll in this course?</span>
        </div>
        <div class="enroll-modal-footer">
            <button class="cta-btn cta-outline" id="enroll-cancel-btn">Cancel</button>
            <button class="cta-btn cta-solid" id="enroll-confirm-btn">Yes, Enroll</button>
        </div>
    </div>
</div>


  {{-- Full-width “Download Curriculum” button --}}
  <div class="cta-download">
    <a href="#" class="cta-btn cta-solid">
      {{-- Download cloud icon SVG --}}
<svg width="30" height="50" viewBox="0 0 59 60" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M26.5029 6.60362C20.0446 6.60362 14.8093 11.839 14.8093 18.2972C14.8093 18.392 14.8107 18.4909 14.8132 18.5961C14.845 19.9555 13.9352 21.1574 12.6182 21.4958C8.83325 22.4684 6.03916 25.9071 6.03916 29.9907C6.03916 34.8345 9.96577 38.7609 14.8093 38.7609H17.7327C19.3473 38.7609 20.6561 40.0697 20.6561 41.6843C20.6561 43.2989 19.3473 44.6077 17.7327 44.6077H14.8093C6.73671 44.6077 0.192383 38.0634 0.192383 29.9907C0.192383 23.9611 3.84136 18.7896 9.04809 16.554C9.92306 7.68512 17.4037 0.756836 26.5029 0.756836C33.0033 0.756836 38.6727 4.29221 41.7016 9.53648C51.1199 9.8434 58.6602 17.5748 58.6602 27.0673C58.6602 36.7546 50.8074 44.6077 41.1198 44.6077C39.5053 44.6077 38.1965 43.2989 38.1965 41.6843C38.1965 40.0697 39.5053 38.7609 41.1198 38.7609C47.5782 38.7609 52.8134 33.5254 52.8134 27.0673C52.8134 20.6091 47.5782 15.3738 41.1198 15.3738C40.7962 15.3738 40.4767 15.3869 40.1604 15.4124C38.9124 15.5134 37.7386 14.8084 37.2417 13.6592C35.4438 9.50286 31.3087 6.60362 26.5029 6.60362ZM29.4263 24.144C31.0409 24.144 32.3497 25.4528 32.3497 27.0673V49.2436L33.2059 48.3873C34.3475 47.2458 36.1986 47.2458 37.3402 48.3873C38.4818 49.5289 38.4818 51.38 37.3402 52.5216L31.4934 58.3684C30.9453 58.9165 30.2016 59.2246 29.4263 59.2246C28.651 59.2246 27.9073 58.9165 27.3592 58.3684L21.5123 52.5216C20.3707 51.38 20.3707 49.5289 21.5123 48.3873C22.654 47.2458 24.505 47.2458 25.6466 48.3873L26.5029 49.2436V27.0673C26.5029 25.4528 27.8117 24.144 29.4263 24.144Z" fill="white"/>
</svg>

      <span>Download Curriculum</span>
    </a>
  </div>
</div>



    {{-- ────────────── Tools & Platforms ────────────── --}}
    @if(!empty($course->tools))
    <hr class="section-divider">
      <h2 class="section-title mt-5">
        Tools &amp; Platforms
      </h2>
      <div class="tools-grid">
        @foreach($course->tools as $tool)
          <div class="tool-card">
            <img src="{{ Storage::url($tool['icon']) }}"
                 alt="{{ $tool['name'] }}"
                 class="tool-icon">
          </div>
        @endforeach
      </div>
    @endif

  </div>
  <script>
  window.courseBalance = @json(auth()->user()->course_balance ?? 0);
 window.enrollUrlBase = @json(route('course.enroll', ['course' => '__ID__'])).replace("__ID__", '');

  window.pricingUrl = '{{ route('pricing.page') }}';
</script>
@vite('resources/js/course.js')
@vite('resources/js/main.js')


@endsection
