@extends('dashboard')

@section('title', 'Pricing')

@section('content')
@push('styles')
  @vite('resources/css/pricing.css')
@endpush

<div class="pricing-container">
  <h2 class="section-title text-center">Our <span>Pricing</span></h2>

  <div class="plan-cards">
    <!-- College Program -->
    <div class="plan-card">
      <div class="plan-badge">College Program</div>
      <p class="price">$20 <span class="tax">+ Tax</span><br>
        <small>(Access to 1 course)</small>
      </p>
      <ul>
        <li><img src="{{ asset('images/icon-college.svg') }}" alt="college"> For Colleges, Universities & group of Students</li>
        <li><img src="{{ asset('images/icon-clock.svg') }}" alt="clock"> Common Timings</li>
      </ul>
      <button onclick="choosePlan('C')">Choose Plan</button>
    </div>

    <!-- Employee Program -->
  <div class="plan-card">
      <div class="plan-badge">Employee Program</div>
      <p class="price">$35 <span class="tax">+ Tax</span><br>
        <small>(Access to 3 course)</small>
      </p>
      <ul>
        <li><img src="{{ asset('images/icon-people.svg') }}" alt="college"> 1-1 Individuals</li>
        <li><img src="{{ asset('images/icon-clock.svg') }}" alt="clock"> Common Timings</li>
      </ul>
      <button onclick="choosePlan('C')">Choose Plan</button>
    </div>

    <!-- Complete Transformation Program -->
    <div class="plan-card">
      <div class="plan-badge">Complete Transformation Program</div>
      <p class="price">$50 <span class="tax">+ Tax</span><br>
        <small>(Access to 5 courses)</small>
      </p>
      <ul>
        <li><img src="{{ asset('images/icon-people.svg') }}" alt="people"> 1-1 Individuals</li>
        <li><img src="{{ asset('images/icon-clock.svg') }}" alt="clock"> Flexible Timings</li>
      </ul>
      <button onclick="choosePlan('A')">Choose Plan</button>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="subscription-modal" class="modal-overlay" style="display: none;">
  <div class="modal-content">
    <h3 id="modal-title">Confirm Subscription</h3>
    <p id="modal-message">Are you sure you want to subscribe to this plan?</p>
    <div class="modal-actions">
      <button id="confirm-subscribe" class="btn-confirm">Yes</button>
      <button id="cancel-subscribe" class="btn-cancel">No</button>
    </div>
  </div>
</div>

@vite('resources/js/pricing.js')
@endsection
