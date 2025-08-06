@extends('layouts.app')
@section('title', 'Login')
@push('styles')
@vite('resources/css/login.css')
@endpush
@section('content')
<div class="container-fluid px-0" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="row g-0 min-vh-100">
        <!-- Left side - Form -->
        <div class="col-lg-5 col-md-6 d-flex align-items-center justify-content-center p-4">
            <div class="w-100" style="max-width: 800px;">
                <div class="bg-white p-4 p-lg-5 rounded-3 shadow-sm">
                    <!-- Title -->
                    <div class="mb-4">
                        <h2 class="fw-bold mb-0 text-center" style="color: #003C71; font-size: 2rem;">
                            Log In
                        </h2>
                    </div>

                    <!-- Form -->
                    <div class="card rounded-4 shadow p-4 mx-auto" style="max-width:800px;">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label text-muted small">Email Address</label>
                            <input id="email"
                                   type="email"
                                   name="email"
                                   class="form-control border-0 border-bottom rounded-0 px-0 py-2"
                                   style="background-color: transparent; border-color: #e0e0e0 !important;"
                                   required>
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <label for="password" class="form-label text-muted small">Password</label>
                            <input id="password"
                                   type="password"
                                   name="password"
                                   class="form-control border-0 border-bottom rounded-0 px-0 py-2"
                                   style="background-color: transparent; border-color: #e0e0e0 !important;"
                                   required>
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label text-muted small" for="remember">
                                Remember Me
                            </label>
                        </div>

                        <!-- Login Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary py-3 fw-bold rounded-2" style="background-color: #003C71; border-color: #003C71; font-size: 1rem;">
                                Login
                            </button>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center mb-4">
                            <small class="text-muted">Don't have an account?
                                <a href="{{ route('register') }}" class="text-decoration-none" style="color: #f57c00;">Register here</a>
                            </small>
                        </div>

                        <!-- Divider -->
                        <div class="position-relative text-center mb-4">
                            <hr class="text-muted">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">or</span>
                        </div>

                        <!-- Social Login Buttons -->
                        <div class="d-grid gap-2">
                           <!-- Google Sign In -->
                            <button type="button" class="btn btn-outline-secondary d-flex align-items-center justify-content-center py-2">
                                <img src="{{ asset('images/google-icon.svg') }}" alt="Google Icon" class="me-2" style="width: 18px; height: 18px;">
                                Sign in with Google
                            </button>

                            <!-- Facebook Sign In -->
                            <button type="button" class="btn btn-outline-secondary d-flex align-items-center justify-content-center py-2" style="background-color: #1877F2; color: white;">
                                <img src="{{ asset('images/facebook-icon.svg') }}" alt="Facebook Icon" class="me-2" style="width: 18px; height: 18px;">
                                Sign in with Facebook
                            </button>

                            <!-- Apple Sign In -->
                           <button type="button" class="btn btn-outline-secondary d-flex align-items-center justify-content-center py-2" style="background-color: #000000; color: white;">
                                <img src="{{ asset('images/apple-icon.svg') }}" alt="Facebook Icon" class="me-2" style="width: 18px; height: 18px;">
                                Sign in with Apple
                            </button>
                        </div>

                        <!-- Terms -->
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                By continuing, you agree to the
                                <a href="#" class="text-decoration-none" style="color: #f57c00;">Terms of Service</a>
                                and
                                <a href="#" class="text-decoration-none" style="color: #f57c00;">Privacy Policy</a>
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <!-- Right side - Illustration -->
        <div class="col-lg-7 col-md-6 d-none d-md-flex align-items-center justify-content-center p-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
            <div class="text-center">
                <img src="{{ asset('images/illustration.svg') }}"
                     alt="Login Illustration"
                     class="img-fluid"
                     style="max-width: 80%; height: auto;">
            </div>
        </div>
    </div>
</div>
@endsection
