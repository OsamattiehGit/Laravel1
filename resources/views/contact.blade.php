@extends('layouts.app')

@push('styles')
  @vite('resources/css/contact.css')
@endpush

@section('content')
  <!-- HEADER -->
  <section class="contact-header">
    <h1>Contact Our Team</h1>
  </section>

  <!-- FORM SECTION -->
  <section class="contact-section">
    <!-- Decorative dots on the left -->
    <img src="{{ asset('images/dots.svg') }}" alt="Dots Pattern" class="dot-pattern">

    <!-- Decorative half-circle on the bottom right -->
    <img src="{{ asset('images/orange-circle.svg') }}" alt="Circle Pattern" class="circle-pattern">

    <div class="contact-box">
      @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
      @endif

      <form action="{{ route('contact.submit') }}" method="POST" class="contact-form">
        @csrf
        <div class="form-grid">
          <div class="form-group">
            <label for="name">Your name*</label>
            <input id="name" name="name" type="text" required>
          </div>

          <div class="form-group">
            <label for="email">Contact email*</label>
            <input id="email" name="email" type="email" required>
          </div>

          <div class="form-group">
            <label for="phone">Phone Number*</label>
            <input id="phone" name="phone" type="text" required>
          </div>

          <div class="form-group">
            <label for="issue">Issue Related to*</label>
            <select id="issue" name="issue" required>
              <option>Course Structure</option>
              <option>Payment Failure</option>
              <option>Other</option>
            </select>
          </div>

          <div class="form-group full-width">
            <label for="message">Your message*</label>
            <textarea id="message" name="message" required></textarea>
          </div>
        </div>

        <p class="privacy-text">
          By submitting this form you agree to our terms and conditions and our Privacy Policy...
        </p>

        <button type="submit" class="btn-submit">Send</button>
      </form>
    </div>
  </section>

  <!-- EMAIL / CALL INFO -->
  <section class="contact-info">
    <div class="info-item">
      <img src="{{ asset('images/email.svg') }}" alt="Email Icon">
      <h4>Email us</h4>
      <p>Email us for general queries, including marketing and partnership opportunities.</p>
      <a href="mailto:hello@ezyskills.com">hello@ezyskills.com</a>
    </div>

    <div class="info-item">
      <img src="{{ asset('images/call.svg') }}" alt="Call Icon">
      <h4>Call us</h4>
      <p>Call us to speak to a member of our team. We are always happy to help.</p>
      <a href="tel:+918888899999">+91 88888 99999</a>
    </div>
  </section>
@endsection
