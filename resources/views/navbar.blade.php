<header style="background-color: white; padding: 3px 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
    <nav style="display: flex; align-items: center; justify-content: space-between;">

        <!-- Logo -->
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="EzySkills Logo" style="height: 50px;">
        </a>

        <!-- Navigation Links -->
        <ul style="list-style: none; display: flex; gap: 25px; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; font-weight: 800px;">
            @php $current = Request::path(); @endphp

            <li><a href="{{ url('/home') }}"
                style="text-decoration: none; color: {{ $current == '/' ? '#f37021' : '#333' }};
                       border-bottom: {{ $current == '/home' ? '2px solid #f37021' : 'none' }};
                       padding-bottom: 4px;"
                onmouseover="this.style.color='#f37021'; this.style.borderBottom='2px solid #f37021';"
                onmouseout="if(location.pathname !== '/home'){ this.style.color='#333'; this.style.borderBottom='none'; }">Home</a></li>

            <li><a href="{{ url('/course-selector') }}"
                style="text-decoration: none; color: {{ $current == 'course-selector' ? '#f37021' : '#333' }};
                       border-bottom: {{ $current == 'course-selector' ? '2px solid #f37021' : 'none' }};
                       padding-bottom: 4px;"
                onmouseover="this.style.color='#f37021'; this.style.borderBottom='2px solid #f37021';"
                onmouseout="if(location.pathname !== '/course-selector'){ this.style.color='#333'; this.style.borderBottom='none'; }">Course Selector</a></li>
                <li><a href="{{ url('/courses') }}"
                style="text-decoration: none; color: {{ $current == 'courses' ? '#f37021' : '#333' }};
                       border-bottom: {{ $current == 'courses' ? '2px solid #f37021' : 'none' }};
                       padding-bottom: 4px;"
                onmouseover="this.style.color='#f37021'; this.style.borderBottom='2px solid #f37021';"
                onmouseout="if(location.pathname !== '/courses'){ this.style.color='#333'; this.style.borderBottom='none'; }">Courses</a></li>
           <li><a href="{{ url('/pricing') }}"
                style="text-decoration: none; color: {{ $current == 'faq' ? '#f37021' : '#333' }};
                       border-bottom: {{ $current == 'pricing' ? '2px solid #f37021' : 'none' }};
                       padding-bottom: 4px;"
                onmouseover="this.style.color='#f37021'; this.style.borderBottom='2px solid #f37021';"
                onmouseout="if(location.pathname !== '/pricing'){ this.style.color='#333'; this.style.borderBottom='none'; }">Prices</a></li>
            <li><a href="{{ url('/faq') }}"
                style="text-decoration: none; color: {{ $current == 'faq' ? '#f37021' : '#333' }};
                       border-bottom: {{ $current == 'faq' ? '2px solid #f37021' : 'none' }};
                       padding-bottom: 4px;"
                onmouseover="this.style.color='#f37021'; this.style.borderBottom='2px solid #f37021';"
                onmouseout="if(location.pathname !== '/faq'){ this.style.color='#333'; this.style.borderBottom='none'; }">FAQ</a></li>

            <li><a href="{{ url('/contact') }}"
                style="text-decoration: none; color: {{ $current == 'contact' ? '#f37021' : '#333' }};
                       border-bottom: {{ $current == 'contact' ? '2px solid #f37021' : 'none' }};
                       padding-bottom: 4px;"
                onmouseover="this.style.color='#f37021'; this.style.borderBottom='2px solid #f37021';"
                onmouseout="if(location.pathname !== '/contact'){ this.style.color='#333'; this.style.borderBottom='none'; }">Contact</a></li>

            <li><a href="{{ url('/about') }}"
                style="text-decoration: none; color: {{ $current == 'about' ? '#f37021' : '#333' }};
                       border-bottom: {{ $current == 'about' ? '2px solid #f37021' : 'none' }};
                       padding-bottom: 4px;"
                onmouseover="this.style.color='#f37021'; this.style.borderBottom='2px solid #f37021';"
                onmouseout="if(location.pathname !== '/about'){ this.style.color='#333'; this.style.borderBottom='none'; }">About US</a></li>
        </ul>

        <!-- Auth Buttons -->
        <div style="display: flex; gap: 10px; align-items: center;">
            @guest
                <a href="{{ route('login') }}" style="padding: 8px 20px; border: 1px solid #f37021; color: #f37021; border-radius: 4px; text-decoration: none;">Log In</a>
                <a href="{{ route('register') }}" style="padding: 8px 20px; background-color: #f37021; color: white; border-radius: 4px; text-decoration: none;">Create Account</a>
            @else
                <a href="{{ route('profile.edit') }}" title="Settings">
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
