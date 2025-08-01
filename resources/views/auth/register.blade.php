
@extends('layouts.app')
@section('title', 'Create Account')
@vite(['resources/css/style.css'])
@section('content')
<div class="auth-container">
    <div class="auth-form-box">
        <h2 class="Register-Title-First">Create</h2>
        <h2 class="Register-Title-Second">Account</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" required>

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>

            <label for="password_confirmation">Repeat Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>

            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember Me</label>
            </div>

            <button type="submit" class="primary-btn">Create Account</button>

            <p class="center-text"><a href="{{ route('login') }}">Already Created? Login Here</a></p>

            <div class="social-login">
                <button type="button" class="google">Sign in with Google</button>
                <button type="button" class="facebook">Sign in with Facebook</button>
                <button type="button" class="apple">Sign in with Apple</button>
            </div>

            <p class="terms">By continuing, you agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></p>
        </form>
    </div>

    <div class="auth-illustration">
        <img src="{{ asset('images/illustration.svg') }}" alt="Illustration">
    </div>
</div>
@endsection
