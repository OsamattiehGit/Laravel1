@extends('dashboard')

@section('title', 'Pricing')

@section('content')
@push('styles')
  @vite('resources/css/pricing.css')
@endpush
<div class="pricing-container">
  <h2 class="text-center section-title">Our <span>Pricing</span></h2>
  <div class="plan-cards">
    <div class="plan-card">
      <h4>College Program</h4>
      <p class="price">₹ 20,000 <span class="tax">+ Tax</span>
        <br><small>(Exclusive of GST & Taxes)</small>
      </p>
      <ul>
        <li>For Colleges, Universities & group of Students</li>
        <li>Common Timings</li>
      </ul>
      <button onclick="choosePlan('C')">Choose Plan</button>
    </div>

    <div class="plan-card highlighted">
      <h4>Employee Program</h4>
      <p class="price">₹ 50,000 <span class="tax">+ Tax</span>
        <br><small>(Exclusive of GST & Taxes)</small>
      </p>
      <ul>
        <li>1-1 Individuals</li>
        <li>Choose Timings</li>
      </ul>
      <button onclick="choosePlan('B')">Choose Plan</button>
    </div>

    <div class="plan-card">
      <h4>Complete Transformation Program</h4>
      <p class="price">₹ 75,000 <span class="tax">+ Tax</span>
        <br><small>(Exclusive of GST & Taxes)</small>
      </p>
      <ul>
        <li>1-1 Individuals</li>
        <li>Flexible Timings</li>
      </ul>
      <button onclick="choosePlan('A')">Choose Plan</button>
    </div>
  </div>
</div>


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

