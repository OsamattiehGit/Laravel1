{{-- resources/views/course.blade.php --}}
@extends('layouts.app')

@section('title', $course->title)

@section('meta')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@vite(['resources/css/course.css','resources/js/course.js'])

@section('styles')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')
@php
  // Hero icon (course logo from storage)
  $icon = $course->image ? asset('storage/'.$course->image)
                         : asset('images/course-default.svg');

  // Casted arrays from model
  $objectives = is_array($course->objectives) ? $course->objectives : [];
  $projects   = is_array($course->projects)   ? $course->projects   : [];
  $tools      = is_array($course->tools)      ? $course->tools      : [];

  // Sectioned content
  $sections = is_array($course->course_content_details) ? $course->course_content_details : [];
@endphp

{{-- ============================== HERO ============================== --}}
<section class="course-hero">
  <div class="container hero-grid">
    <div class="hero-emblem">
      <img src="{{ $icon }}" alt="{{ $course->title }} icon">
    </div>

    <div class="hero-copy">
      <h1 class="hero-title">{{ $course->title }}</h1>
      <p class="hero-subtitle">Basic to Advance Level Coding</p>
    </div>
  </div>
</section>

{{-- ============================== MAIN ============================== --}}
<section class="container course-wrap">
  <div class="course-grid">
    {{-- LEFT: About + Objectives --}}
    <aside class="col-left">
      <h2 class="split-title">
        <span>About
        The
       Course</span>
      </h2>

      <div class="about-text">{!! nl2br(e($course->description)) !!}</div>

      @if($objectives)
        <h3 class="subheading">Objectives</h3>
        <ul class="objectives">
          @foreach($objectives as $line)
            <li>
              <span class="tick"></span>
              <span>{{ $line }}</span>
            </li>
          @endforeach
        </ul>
      @endif
    </aside>

    {{-- RIGHT: Course Content --}}
    <main class="col-right">
      <h2 class="split-title right">
        <span>Course
        Content</span>
      </h2>

      <div class="acc">
        @forelse($sections as $i => $sec)
          @php
            $id    = 'acc-'.($i+1);
            $items = $sec['items'] ?? [];
          @endphp

          <article class="acc-item">
            @if($isEnrolled)
              <input id="{{ $id }}" class="acc-toggle" type="checkbox" hidden>
              <label class="acc-head" for="{{ $id }}">
                <span class="acc-num">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="acc-title">{{ $sec['section'] ?? 'Untitled Section' }}</span>
                <span class="acc-plus" aria-hidden="true">+</span>
              </label>

              <div class="acc-body">
                @if(!$items)
                  <p class="muted">No content has been added yet.</p>
                @else
                  @foreach($items as $item)
                    @php
                      $type = $item['type']  ?? 'text';
                      $val  = trim($item['value'] ?? '');
                    @endphp

                    @if($type === 'text')
                      <p class="acc-text">{{ $val }}</p>

                    @elseif($type === 'image')
                      <figure class="acc-figure">
                        @if(Str::startsWith($val, 'http'))
                          <img src="{{ $val }}" alt="{{ $sec['section'] ?? 'Section image' }}">
                        @else
                          <img src="{{ Storage::url($val) }}" alt="{{ $sec['section'] ?? 'Section image' }}">
                        @endif
                      </figure>

                    @elseif($type === 'video')
                      @php
                        // Check if it's a YouTube URL or file path
                        if (Str::startsWith($val, 'http')) {
                          // YouTube → embed
                          $src = $val;
                          if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([A-Za-z0-9_-]{6,})~i', $val, $m)) {
                            $src = 'https://www.youtube.com/embed/'.$m[1];
                          }

                          if (str_contains($src, 'youtube.com/embed')) {
                            $isYoutube = true;
                            $youtubeSrc = $src;
                          } else {
                            $isYoutube = false;
                            $videoSrc = $src;
                          }
                        } else {
                          // File path
                          $isYoutube = false;
                          $videoSrc = Storage::url($val);
                        }
                      @endphp

                      @if($isYoutube)
                        <div class="video-embed"><iframe src="{{ $youtubeSrc }}" allowfullscreen></iframe></div>
                      @else
                        <video class="video-file" controls preload="metadata"><source src="{{ $videoSrc }}"></video>
                      @endif
                    @endif
                  @endforeach
                @endif
              </div>
            @else
              <!-- Locked content for non-enrolled users -->
              <div class="acc-head locked-content" onclick="showEnrollmentRequired()">
                <span class="acc-num">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="acc-title">{{ $sec['section'] ?? 'Untitled Section' }}</span>
                <span class="acc-lock" aria-hidden="true">🔒</span>
              </div>
            @endif
          </article>
        @empty
          <article class="acc-item">
            <div class="acc-body"><p class="muted">No modules yet.</p></div>
          </article>
        @endforelse
      </div>
    </main>
  </div>

  <hr class="section-break">

  {{-- ============================ PROJECTS ============================ --}}
  @if($projects)
    <h3 class="subheading projects-head">Angular JS Projects</h3>
    <div class="projects">
      @foreach($projects as $p)
        <div class="project">
          <div class="project-icon">
            @if(!empty($p['icon']))
              <img src="{{ asset('storage/'.$p['icon']) }}" alt="Project icon">
            @else
              <img src="{{ asset('images/project-icon.svg') }}" alt="Project icon">
            @endif
          </div>
          <div class="project-title">{{ $p['title'] ?? 'Project' }}</div>
          @if(!empty($p['subtitle']))
            <div class="project-sub">{{ $p['subtitle'] }}</div>
          @endif
        </div>
      @endforeach
    </div>
  @endif

  {{-- ============================== CTA ============================== --}}
<div class="cta">
  <div class="cta-copy">
    Wanna check more<br>about the course?
  </div>

  <div class="cta-actions">
    <a href="#demo" class="cta-btn cta-outline">
       <svg width="30" height="30" viewBox="0 0 46 43" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M0.769531 4.68515C0.769531 2.23029 2.75959 0.240234 5.21445 0.240234H40.7738C43.2287 0.240234 45.2187 2.23029 45.2187 4.68515V29.1322C45.2187 31.5871 43.2287 33.5771 40.7738 33.5771H25.2166V38.0221H31.884C33.1114 38.0221 34.1064 39.0171 34.1064 40.2445C34.1064 41.472 33.1114 42.467 31.884 42.467H14.1043C12.8769 42.467 11.8818 41.472 11.8818 40.2445C11.8818 39.0171 12.8769 38.0221 14.1043 38.0221H20.7717V33.5771H5.21445C2.75959 33.5771 0.769531 31.5871 0.769531 29.1322V4.68515ZM40.7738 29.1322V4.68515H5.21445V29.1322H40.7738Z" fill="#F98149"/>
</svg>
      <span>Demo</span>
    </a>

    @if(!$isEnrolled)
    <button id="enroll-btn"
            class="cta-btn cta-outline"
            data-course-id="{{ $course->id }}"
            data-course-title="{{ $course->title }}">
      <svg width="30" height="30" viewBox="0 0 30 37" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4.22064 15.8512V4.06131C3.21156 4.06131 2.39352 3.26145 2.39352 2.2748C2.39352 1.28814 3.21156 0.488281 4.22064 0.488281H26.1461C27.1552 0.488281 27.9732 1.28814 27.9732 2.2748C27.9732 3.26145 27.1552 4.06131 26.1461 4.06131V15.8512C27.8486 17.6747 28.7627 19.4863 29.2498 20.9149C29.5166 21.6976 29.654 22.3615 29.7248 22.8462C29.7603 23.0888 29.7791 23.2869 29.7892 23.4336C29.7941 23.507 29.7968 23.5676 29.7985 23.6144L29.7996 23.6562L29.7999 23.6742L29.8003 23.6964V23.7053V23.7092V23.7112C29.8003 23.7112 29.7919 23.4766 29.8003 23.713C29.8003 24.6997 28.9823 25.4995 27.9732 25.4995H17.0105V34.4321C17.0105 35.4188 16.1925 36.2186 15.1834 36.2186C14.1742 36.2186 13.3562 35.4188 13.3562 34.4321V25.4995H2.39352C1.38444 25.4995 0.566406 24.6997 0.566406 23.713C0.566406 22.8197 0.566406 23.7112 0.566406 23.7112V23.7092L0.566425 23.7053L0.566479 23.6964L0.566753 23.6742C0.567009 23.6578 0.567484 23.6378 0.568252 23.6144C0.569786 23.5676 0.572582 23.507 0.577589 23.4336C0.587583 23.2869 0.606438 23.0888 0.641866 22.8462C0.712685 22.3615 0.850121 21.6976 1.11695 20.9149C1.60399 19.4863 2.51821 17.6747 4.22064 15.8512ZM22.4918 4.06131H7.87488V16.5669C7.87488 17.0407 7.68238 17.4952 7.33974 17.8302C5.79213 19.3433 5.01685 20.8285 4.62495 21.9265H25.7417C25.3498 20.8285 24.5746 19.3433 23.027 17.8302C22.6844 17.4952 22.4918 17.0407 22.4918 16.5669V4.06131Z" fill="#F98149"/>

</svg>
      <span>Enroll Now</span>
    </button>
    @else
    <div class="enrollment-success">
      <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; text-align: center;">
        <strong>✓ Already Enrolled!</strong><br>
        <small>You have access to all course content.</small>
      </div>
    </div>
    @endif
  </div>

  @if($isEnrolled)
    <a class="cta-btn cta-solid cta-download"
       href="{{ $course->curriculum ? asset('storage/'.$course->curriculum) : '#' }}"
       @if(!$course->curriculum) aria-disabled="true" onclick="return false" @endif>
       <svg width="50" height="50" viewBox="0 0 59 60" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M26.5029 6.60362C20.0446 6.60362 14.8093 11.839 14.8093 18.2972C14.8093 18.392 14.8107 18.4909 14.8132 18.5961C14.845 19.9555 13.9352 21.1574 12.6182 21.4958C8.83325 22.4684 6.03916 25.9071 6.03916 29.9907C6.03916 34.8345 9.96577 38.7609 14.8093 38.7609H17.7327C19.3473 38.7609 20.6561 40.0697 20.6561 41.6843C20.6561 43.2989 19.3473 44.6077 17.7327 44.6077H14.8093C6.73671 44.6077 0.192383 38.0634 0.192383 29.9907C0.192383 23.9611 3.84136 18.7896 9.04809 16.554C9.92306 7.68512 17.4037 0.756836 26.5029 0.756836C33.0033 0.756836 38.6727 4.29221 41.7016 9.53648C51.1199 9.8434 58.6602 17.5748 58.6602 27.0673C58.6602 36.7546 50.8074 44.6077 41.1198 44.6077C39.5053 44.6077 38.1965 43.2989 38.1965 41.6843C38.1965 40.0697 39.5053 38.7609 41.1198 38.7609C47.5782 38.7609 52.8134 33.5254 52.8134 27.0673C52.8134 20.6091 47.5782 15.3738 41.1198 15.3738C40.7962 15.3738 40.4767 15.3869 40.1604 15.4124C38.9124 15.5134 37.7386 14.8084 37.2417 13.6592C35.4438 9.50286 31.3087 6.60362 26.5029 6.60362ZM29.4263 24.144C31.0409 24.144 32.3497 25.4528 32.3497 27.0673V49.2436L33.2059 48.3873C34.3475 47.2458 36.1986 47.2458 37.3402 48.3873C38.4818 49.5289 38.4818 51.38 37.3402 52.5216L31.4934 58.3684C30.9453 58.9165 30.2016 59.2246 29.4263 59.2246C28.651 59.2246 27.9073 58.9165 27.3592 58.3684L21.5123 52.5216C20.3707 51.38 20.3707 49.5289 21.5123 48.3873C22.654 47.2458 24.505 47.2458 25.6466 48.3873L26.5029 49.2436V27.0673C26.5029 25.4528 27.8117 24.144 29.4263 24.144Z" fill="white"/>
</svg>
      <span>Download Curriculum</span>
    </a>
  @else
    <button class="cta-btn cta-solid cta-download" onclick="showEnrollmentRequired()">
      <svg width="50" height="50" viewBox="0 0 59 60" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M26.5029 6.60362C20.0446 6.60362 14.8093 11.839 14.8093 18.2972C14.8093 18.392 14.8107 18.4909 14.8132 18.5961C14.845 19.9555 13.9352 21.1574 12.6182 21.4958C8.83325 22.4684 6.03916 25.9071 6.03916 29.9907C6.03916 34.8345 9.96577 38.7609 14.8093 38.7609H17.7327C19.3473 38.7609 20.6561 40.0697 20.6561 41.6843C20.6561 43.2989 19.3473 44.6077 17.7327 44.6077H14.8093C6.73671 44.6077 0.192383 38.0634 0.192383 29.9907C0.192383 23.9611 3.84136 18.7896 9.04809 16.554C9.92306 7.68512 17.4037 0.756836 26.5029 0.756836C33.0033 0.756836 38.6727 4.29221 41.7016 9.53648C51.1199 9.8434 58.6602 17.5748 58.6602 27.0673C58.6602 36.7546 50.8074 44.6077 41.1198 44.6077C39.5053 44.6077 38.1965 43.2989 38.1965 41.6843C38.1965 40.0697 39.5053 38.7609 41.1198 38.7609C47.5782 38.7609 52.8134 33.5254 52.8134 27.0673C52.8134 20.6091 47.5782 15.3738 41.1198 15.3738C40.7962 15.3738 40.4767 15.3869 40.1604 15.4124C38.9124 15.5134 37.7386 14.8084 37.2417 13.6592C35.4438 9.50286 31.3087 6.60362 26.5029 6.60362ZM29.4263 24.144C31.0409 24.144 32.3497 25.4528 32.3497 27.0673V49.2436L33.2059 48.3873C34.3475 47.2458 36.1986 47.2458 37.3402 48.3873C38.4818 49.5289 38.4818 51.38 37.3402 52.5216L31.4934 58.3684C30.9453 58.9165 30.2016 59.2246 29.4263 59.2246C28.651 59.2246 27.9073 58.9165 27.3592 58.3684L21.5123 52.5216C20.3707 51.38 20.3707 49.5289 21.5123 48.3873C22.654 47.2458 24.505 47.2458 25.6466 48.3873L26.5029 49.2436V27.0673C26.5029 25.4528 27.8117 24.144 29.4263 24.144Z" fill="white"/>
</svg>
      <span>Download Curriculum</span>
    </button>
  @endif
</div>

  {{-- ======================= TOOLS & PLATFORMS ======================= --}}
  @if($tools)
    <h3 class="subheading">Tools &amp; Platforms</h3>
    <div class="tools">
      @foreach($tools as $t)
        <div class="tool" title="{{ $t['name'] ?? '' }}">
          @if(!empty($t['icon']))
            <img src="{{ asset('storage/'.$t['icon']) }}" alt="{{ $t['name'] ?? 'Tool' }}">
          @else
            <img src="{{ asset('images/tool-default.svg') }}" alt="{{ $t['name'] ?? 'Tool' }}">
          @endif
        </div>
      @endforeach
    </div>
  @endif
</section>

{{-- Enroll Modal + Toast (used by course.js) --}}
<div id="enroll-modal" class="enroll-modal-backdrop" style="display:none">
  <div class="enroll-modal-content">
    <div class="enroll-modal-header">
      <img class="enroll-modal-icon" src="{{ asset('storage/'.$course->image) }}" alt="" width="56" height="56">
      <h5>Confirm Enrollment</h5>
    </div>
    <div id="enroll-modal-message" class="enroll-modal-body"></div>
    <div class="enroll-modal-footer">
      <button id="enroll-cancel-btn" class="btn btn-outline">Cancel</button>
      <button id="enroll-confirm-btn" class="btn btn-solid">Confirm</button>
    </div>
  </div>
</div>
<div id="custom-toast" style="display:none"></div>
@endsection

@section('scripts')
  <script>
    window.enrollUrlBase = "{{ url('/course') }}";
    window.pricingUrl    = "{{ url('/pricing') }}";
    window.courseBalance = {{ (int) (auth()->user()->course_balance ?? 0) }};
    
    // Define the enrollment required function directly here
    window.showEnrollmentRequired = function() {
      console.log('showEnrollmentRequired called');
      
      // Try to use the toast system first
      if (typeof showToast === 'function') {
        showToast("You have to be enrolled to access Course content", false);
      } else {
        // Fallback to alert
        alert("You have to be enrolled to access Course content");
      }
    };
    
    console.log('Course page loaded with showEnrollmentRequired function');
  </script>
@endsection
