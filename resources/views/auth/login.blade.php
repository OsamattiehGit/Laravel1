@extends('layouts.app')

@vite(['resources/css/login.css'])
<!DOCTYPE html>
@section('content')
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | EZY Skills</title>
</head>
<body>
    <!-- MAIN SECTION -->
    <div class="main-container">
        <div class="login-form">

            <form method="POST" action="/login">
    @csrf

    <h2 class="login-heading">Log In</h2>

    <div class="form-group">
        <input type="email" name="email" placeholder="Email Address" required>
    </div>

    <div class="form-group">
        <input type="password" name="password" placeholder="Password" required>
    </div>

    <div class="checkbox-group">
        <label><input type="checkbox" name="remember"> Remember Me</label>
    </div>

    <button type="submit" class="btn login-btn">Login</button>

    <p class="already">Don't have an account? <a href="/register">Register Here</a></p>

    <div class="divider"><span>or</span></div>

    <button class="btn social-btn google"><img src="/images/google-icon.svg"> Login with Google</button>
    <button class="btn social-btn facebook"><img src="/images/facebook-icon.svg"> Login with Facebook</button>
    <button class="btn social-btn apple"><img src="/images/apple-icon.svg"> Login with Apple</button>

    <p class="terms">By continuing, you agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></p>
</form>

        </div>

     <div class="illustration">
    <img src="{{ asset('images/illustration.svg') }}" alt="Illustration" />
</div>
    </div>

</body>
</html>
@endsection
