@extends('layouts.app')

@push('styles')
  @vite('resources/css/contact.css')
@endpush

@section('title', 'Contact Us - EzySkills')

@section('content')
  <div class="contact-container">
    <h1 class="contact-title">Contact Us</h1>
    <p class="contact-subtitle">We'd love to hear from you. Fill out the form below or email us directly.</p>

    <form action="#" method="POST" class="contact-form">
      @csrf
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
      <button type="submit">Send Message</button>
    </form>
  </div>
@endsection
