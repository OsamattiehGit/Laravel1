<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title','EzySkills')</title>

  {{-- Bootstrap CSS --}}
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr"
    crossorigin="anonymous"
  />

  {{-- Global CSS via Vite --}}
  @vite([
    'resources/css/app.css',
    'resources/css/register.css',
    'resources/css/footer.css',
    'resources/css/pricing.css',
  ])

  <!-- Chat Widget Styles -->
  <link rel="stylesheet" href="{{ asset('css/chat-widget.css') }}">

  {{-- Page-specific CSS --}}
  @stack('styles')
</head>

<body>
  @include('navbar')

  <main>
    @yield('content')
  </main>

  @include('footer')

  {{-- Bootstrap Bundle JS --}}
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
    crossorigin="anonymous"
  ></script>

  {{-- Global JS via Vite --}}
  @vite([
    'resources/js/app.js',
    'resources/js/main.js',
     'resources/js/admin.js',
     'resources/js/home.js',
  ])

  <!-- Chat Widget Script -->
  <script src="{{ asset('js/chat-widget.js') }}"></script>

  {{-- Page-specific JS and variables --}}
  <script>
    window.courseBalance = @json(auth()->user()->course_balance ?? 0);
    window.enrollUrlBase = @json(url('course'));
    window.pricingUrl = @json(route('pricing.page'));
    window.isAuthenticated = @json(auth()->check());
    window.loginUrl = @json(route('login'));
    window.currentUserId = @json(auth()->id() ?? null);
  </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
