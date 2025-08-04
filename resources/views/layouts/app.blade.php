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
    <script> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous"> </script>
    <script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    </script>
  @endpush

  {{-- Render the stack --}}
  @stack('scripts')
</body>
</html>
