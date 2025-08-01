@extends('layouts.app')

@section('content')
<div class="container py-5">
  <h1 class="text-center mb-4">Choose a Plan</h1>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @elseif(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="row">
    @foreach($plans as $key => $plan)
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <div class="card-header bg-orange text-white text-center">
            {{ $plan['label'] }}
          </div>
          <div class="card-body text-center">
            <h2>${{ number_format($plan['price'],2) }}</h2>
            <p>Enroll in {{ $plan['allowance'] }} {{ Str::plural('course',$plan['allowance']) }}</p>
            <form method="POST" action="{{ route('pricing.subscribe') }}"
                  onsubmit="return confirm('Subscribe to {{ $plan['label'] }}?');">
              @csrf
              <input type="hidden" name="plan" value="{{ $key }}">
              <button type="submit" class="btn btn-outline-orange">
                Choose Plan
              </button>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection
