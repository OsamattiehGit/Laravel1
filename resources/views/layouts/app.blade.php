<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title','EzySkills')</title>

  {{-- Global CSS --}}
  @vite([
    'resources/css/style.css',
    'resources/css/footer.css',
    'resources/css/pricing.css',

  ])

  {{-- Page-specific CSS --}}
  @stack('styles')
</head>
<body>
  @include('navbar')

  <main>
    @yield('content')
  </main>

  @include('footer')

  {{-- Global JS --}}
  @vite([
    'resources/js/main.js',
    'resources/js/admin.js',
    'resources/js/home.js',
  ])

  {{-- Page-specific JS and vars --}}
  @push('scripts')
    <script>
      // Expose to course.js:
      window.courseBalance = @json(auth()->user()->course_balance ?? 0);
      window.enrollUrlBase = @json(url('course'))      // => "/course"
        // no more %22 nonsense
      window.pricingUrl   = @json(route('pricing.page'));
    </script>
    <script src="{{ asset('js/course.js') }}"></script>
  @endpush

  {{-- Render the stack --}}
  @stack('scripts')
</body>
</html>
