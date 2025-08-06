@php
    $current = Request::segment(1); // e.g. "about", "pricing", "faq", etc
    // Priority: FAQ/pricing orange > about blue > default white
    if ($current === 'faq' || $current === 'contact') {
        $navbarBg = '#f3702b';
        $navbarText = 'white';
    } elseif ($current === 'about'|| $current === 'pricing' || $current === 'profile') {
        $navbarBg = '#003366';
        $navbarText = 'white';
    } else {
        $navbarBg = 'white';
        $navbarText = '#333';
    }
@endphp

<header style="background-color: {{ $navbarBg }}; padding: 0 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 0;">
    <nav style="display: flex; align-items: center; justify-content: space-between; margin:0; padding:0;">

        <!-- Logo -->
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="EzySkills Logo" style="height: 62px;">
        </a>

        <!-- Navigation Links -->
        <ul style="
            list-style: none;
            display: flex;
            gap: 25px;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
        ">
            @php $current = Request::segment(1); @endphp
            <li><a href="{{ url('/home') }}"
                style="text-decoration: none; color: {{ $navbarText }};
                       border-bottom: {{ $current == 'home' ? '2px solid #f37021' : 'none' }};
                       padding-bottom: 4px;
                       transition: color 0.15s, border-bottom 0.15s;">
                Home</a></li>

            <li><a href="{{ url('/course-selector') }}"
                style="text-decoration: none; color: {{ $navbarText }};
                       border-bottom: {{ $current == 'course-selector' ? '2px solid #f37021' : 'none' }};
                       padding-bottom: 4px;
                       transition: color 0.15s, border-bottom 0.15s;">
                Course Selector</a></li>

            <li><a href="{{ url('/courses') }}"
                style="text-decoration: none; color: {{ $navbarText }};
                       border-bottom: {{ $current == 'courses' ? '2px solid #f37021' : 'none' }};
                       padding-bottom: 4px;
                       transition: color 0.15s, border-bottom 0.15s;">
                Courses</a></li>

            <li><a href="{{ url('/pricing') }}"
                style="text-decoration: none; color: {{ $navbarText }};
                       border-bottom: {{ $current == 'pricing' ? '2px solid #f37021' : 'none' }};
                       padding-bottom: 4px;
                       transition: color 0.15s, border-bottom 0.15s;">
                Prices</a></li>

            <li><a href="{{ url('/faq') }}"
                style="text-decoration: none; color: {{ $navbarText }};
                       border-bottom: {{ $current == 'faq' ? '2px solid #f37021' : 'none' }};
                       padding-bottom: 4px;
                       transition: color 0.15s, border-bottom 0.15s;">
                FAQ</a></li>

            <li><a href="{{ url('/contact') }}"
                style="text-decoration: none; color: {{ $navbarText }};
                       border-bottom: {{ $current == 'contact' ? '2px solid #f37021' : 'none' }};
                       padding-bottom: 4px;
                       transition: color 0.15s, border-bottom 0.15s;">
                Contact</a></li>

            <li><a href="{{ url('/about') }}"
                style="text-decoration: none; color: {{ $navbarText }};
                       border-bottom: {{ $current == 'about' ? '2px solid #f37021' : 'none' }};
                       padding-bottom: 4px;
                       transition: color 0.15s, border-bottom 0.15s;">
                About Us</a></li>
        </ul>

        <!-- Auth Buttons -->
        <div style="display: flex; gap: 10px; align-items: center;">
            @guest
                <a href="{{ route('login') }}" style="padding: 8px 20px; background-color: #f37021; color: white; border-radius: 4px; text-decoration: none;border: 1px solid black;">Login</a>

                <a href="{{ route('register') }}" style="border: black;padding: 8px 20px; background-color: #f37021; color: white; border-radius: 4px; text-decoration: none;border: 1px solid black;">Create Account</a>
            @else
                <a href="{{ url('admin/courses') }}" title="Settings">
                    <img src="{{ asset('images/settings-icon.svg') }}" alt="Settings" style="height: 24px;">
                </a>
                <a href="{{ url('/profile') }}" title="Profile">
                    <img src="{{ asset('images/profile-icon.png') }}" alt="Profile" style="height: 28px; border-radius: 50%;">
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="padding: 8px 20px; background-color: #f37021; color: white; border: none; border-radius: 4px; cursor: pointer;">Logout</button>
                </form>
            @endguest
        </div>
    </nav>
</header>
