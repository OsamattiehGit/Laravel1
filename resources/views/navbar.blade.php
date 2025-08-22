@php
    $current = Request::segment(1); // e.g. "about", "pricing", "faq", etc
    // Handle homepage case (empty segment)
    if (empty($current)) {
        $current = 'home';
    }
    
    // Priority: FAQ/pricing orange > about blue > default white
    if ($current === 'faq' || $current === 'contact') {
        $navbarBg = '#f3702b';
        $navbarText = 'white';
        // FAQ & Contact: Login = orange bg + white border + white text, Create = white bg + black text
        $loginBg = '#f3702b';
        $loginBorder = '1px solid white';
        $loginText = 'white';
        $createBg = 'white';
        $createBorder = '1px solid white';
        $createText = 'black';
        // Logout button: white background + black text
        $logoutBg = 'white';
        $logoutText = 'black';
        $logoutBorder = '1px solid white';
    } elseif ($current === 'about'|| $current === 'pricing' || $current === 'profile') {
        $navbarBg = '#003366';
        $navbarText = 'white';
        // Pricing & About: Login = blue bg + white border + white text, Create = white bg + black text
        $loginBg = '#003366';
        $loginBorder = '1px solid white';
        $loginText = 'white';
        $createBg = 'white';
        $createBorder = '1px solid white';
        $createText = 'black';
        // Logout button: white background + black text
        $logoutBg = 'white';
        $logoutText = 'black';
        $logoutBorder = '1px solid white';
    } else {
        $navbarBg = 'white';
        $navbarText = '#333';
        // Rest pages: Login = white bg + orange border + orange text, Create = orange bg + white text
        $loginBg = 'white';
        $loginBorder = '1px solid #f3702b';
        $loginText = '#f3702b';
        $createBg = '#f3702b';
        $createBorder = '1px solid #f3702b';
        $createText = 'white';
        // Logout button: orange background + white text (keep as is)
        $logoutBg = '#f37021';
        $logoutText = 'white';
        $logoutBorder = 'none';
    }
@endphp

<header style="background-color: {{ $navbarBg }}; padding: 20px 5px 0 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 0;">
    <nav style="display: flex; align-items: center; justify-content: space-between; margin:0; padding:0; position: relative;">

        <!-- Logo -->
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="EzySkills Logo" style="height: 62px;padding-left:60px">
        </a>

        <!-- Mobile Menu Toggle -->
        <button id="mobile-toggle" style="
            display: none;
            background: none;
            border: 1px solid {{ $navbarText }};
            color: {{ $navbarText }};
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 18px;
        ">☰</button>

        <!-- Navigation Links -->
        <ul id="nav-menu" style="
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
        <div id="auth-buttons" style="display: flex; gap: 10px; align-items: center;">
            @guest
                <a href="{{ route('login') }}" style="
                    padding: 8px 20px; 
                    background-color: {{ $loginBg }}; 
                    color: {{ $loginText }}; 
                    border-radius: 4px; 
                    text-decoration: none;
                    border: {{ $loginBorder }};
                    margin-right: 10px;
                    transition: all 0.2s ease;
                ">Login</a>

                <a href="{{ route('register') }}" style="
                    padding: 8px 20px; 
                    background-color: {{ $createBg }}; 
                    color: {{ $createText }}; 
                    border-radius: 4px; 
                    text-decoration: none;
                    border: {{ $createBorder }};
                    margin-right: 40px;
                    transition: all 0.2s ease;
                ">Create Account</a>
            @else
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" title="Admin Panel">
                        <img src="{{ asset('images/settings-icon.svg') }}" alt="Admin Panel" style="height: 24px;padding-right:10px">
                    </a>
                @endif
                <a href="{{ url('/profile') }}" title="Profile">
                    <img src="{{ asset('images/profile-icon.png') }}" alt="Profile" style="height: 28px; border-radius: 50%;">
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;padding-left:10px;padding-right:50px">
                    @csrf
                    <button type="submit" style="
                        padding: 8px 20px; 
                        background-color: {{ $logoutBg }}; 
                        color: {{ $logoutText }}; 
                        border: {{ $logoutBorder }}; 
                        border-radius: 4px; 
                        cursor: pointer;
                        transition: all 0.2s ease;
                    ">Logout</button>
                </form>
            @endguest
        </div>
    </nav>
</header>

<style>
    /* Hover effects for auth buttons */
    #auth-buttons a:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
    
    /* Hover effects for logout button */
    #auth-buttons button[type="submit"]:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
    
    @media (max-width: 768px) {
        nav a img {
            padding-left: 10px !important;
            height: 50px !important;
        }
        
        #mobile-toggle {
            display: block !important;
        }

        #nav-menu {
            display: none !important;
            position: fixed !important;
            top: 80px !important;
            left: 0 !important;
            right: 0 !important;
            background-color: {{ $navbarBg }};
            flex-direction: column !important;
            gap: 0 !important;
            padding: 15px !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 1000;
            max-height: calc(100vh - 80px) !important;
            overflow-y: auto !important;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        #nav-menu.show {
            display: flex !important;
        }

        #nav-menu li {
            text-align: center;
            padding: 12px 0 !important;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            min-height: 44px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        #nav-menu li:last-child {
            border-bottom: none;
        }

        #nav-menu li a {
            width: 100% !important;
            padding: 8px 0 !important;
            display: block !important;
        }

        #auth-buttons {
            display: none !important;
            position: fixed !important;
            top: 80px !important;
            left: 0 !important;
            right: 0 !important;
            background-color: {{ $navbarBg }};
            justify-content: center !important;
            flex-wrap: wrap;
            padding: 15px !important;
            margin-top: 0 !important;
            z-index: 1000;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        #auth-buttons.show {
            display: flex !important;
        }

        nav {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 480px) {
        nav a img {
            padding-left: 5px !important;
            height: 45px !important;
        }
        
        #auth-buttons a, #auth-buttons button {
            padding: 6px 12px !important;
            font-size: 14px !important;
        }
        
        #nav-menu {
            top: 70px !important;
            max-height: calc(100vh - 70px) !important;
        }
        
        #auth-buttons {
            top: 70px !important;
        }
        
        #nav-menu li {
            padding: 10px 0 !important;
            min-height: 40px !important;
        }
        
        #nav-menu li a {
            font-size: 16px !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('mobile-toggle');
        const menu = document.getElementById('nav-menu');
        const authButtons = document.getElementById('auth-buttons');
        const body = document.body;

        if (toggle) {
            toggle.addEventListener('click', function() {
                menu.classList.toggle('show');
                authButtons.classList.toggle('show');
                
                // Prevent body scroll when menu is open
                if (menu.classList.contains('show')) {
                    body.style.overflow = 'hidden';
                } else {
                    body.style.overflow = '';
                }
            });
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('nav') && menu.classList.contains('show')) {
                menu.classList.remove('show');
                authButtons.classList.remove('show');
                body.style.overflow = '';
            }
        });
        
        // Close menu when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && menu.classList.contains('show')) {
                menu.classList.remove('show');
                authButtons.classList.remove('show');
                body.style.overflow = '';
            }
        });
    });
</script>
