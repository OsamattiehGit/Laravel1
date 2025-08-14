@extends('dashboard')

@section('title', 'Pricing')

@section('content')
@push('styles')
    @vite('resources/css/pricing.css')
@endpush

<section class="pricing-section">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h2 class="pricing-title text-center">Our <span class="text-orange">Pricing</span></h2>
      </div>
    </div>

 <div class="row justify-content-center align-items-stretch g-0">

      <!-- College Program -->
      <div class="col-lg-auto col-md-auto col-sm-6 d-flex justify-content-center">

        <div class="plan-card text-center">
          <div class="plan-badge">College Program</div>
          <div class="plan-price">
            <span class="currency">$ 19.99 </span><span class="tax-info">+ Tax</span>
            <small>(Access to 1 Course)</small>
          </div>
          <ul class="plan-features">
            <li>
              <div class="feature-icon">
                <img src="{{ asset('images/college-icon.svg') }}" alt="college">
              </div>
              <span>For Colleges, Universities & group of Students</span>
            </li>
            <li>
              <div class="feature-icon">
                <img src="{{ asset('images/icon-clock.svg') }}" alt="clock">
              </div>
              <span>Common Timings</span>
            </li>
          </ul>
          <button class="btn choose-btn" onclick="choosePlan('C')">Choose Plan</button>
        </div>
      </div>

      <!-- Employee Program (Highlighted) -->
      <div class="col-lg-auto col-md-auto col-sm-6 d-flex justify-content-center">

        <div class="plan-card text-center highlighted">
          <div class="plan-badge">Employee Program</div>
          <div class="plan-price">
            <span class="currency">$</span> 34.99 <span class="tax-info">+ Tax</span>
            <small>(Access to 3 Courses)</small>
          </div>
          <ul class="plan-features">
            <li>
              <div class="feature-icon">
                <img src="{{ asset('images/icon-people.svg') }}" alt="people">
              </div>
              <span>1-1 Individuals</span>
            </li>
            <li>
              <div class="feature-icon">
                <img src="{{ asset('images/icon-clock.svg') }}" alt="clock">
              </div>
              <span>Choose Timings</span>
            </li>
          </ul>
          <button class="btn choose-btn" onclick="choosePlan('B')">Choose Plan</button>
        </div>
      </div>

      <!-- Complete Transformation Program -->
<div class="col-lg-auto col-md-auto col-sm-6 d-flex justify-content-center">

        <div class="plan-card text-center">
          <div class="plan-badge">Complete Transformation Program</div>
          <div class="plan-price">
            <span class="currency">$</span> 49.99 <span class="tax-info">+ Tax</span>
            <small>(Access to 5 Courses)</small>
          </div>
          <ul class="plan-features">
            <li>
              <div class="feature-icon">
                <img src="{{ asset('images/icon-people.svg') }}" alt="people">
              </div>
              <span>1-1 Individuals</span>
            </li>
            <li>
              <div class="feature-icon">
                <img src="{{ asset('images/icon-clock.svg') }}" alt="clock">
              </div>
              <span>Flexible Timings</span>
            </li>
          </ul>
          <button class="btn choose-btn" onclick="choosePlan('A')">Choose Plan</button>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Toast for pricing notifications -->
<div id="pricing-toast" style="display:none;"></div>

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
<div>

@vite('resources/js/pricing.js')
@endsection
