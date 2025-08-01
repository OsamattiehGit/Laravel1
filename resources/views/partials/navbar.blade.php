<header>
    <nav style="display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; background: #fff;">
        <div><strong>EzySkills</strong></div>

        <div style="display: flex; gap: 15px; align-items: center;">
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('/courses') }}">Courses</a>
            <a href="{{ url('/pricing') }}">Pricing</a>
            <a href="{{ url('/faq') }}">FAQ</a>
            <a href="{{ url('/contact') }}">Contact</a>
            <a href="{{ url('/about') }}">About</a>

            @guest
                <a href="{{ url('/login') }}">Login</a>
                <a href="{{ url('/register') }}">Create Account</a>
            @else
                <a href="{{ url('/profile') }}">Profile</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-button">Logout</button>
                </form>
            @endguest
        </div>
    </nav>
</header>
