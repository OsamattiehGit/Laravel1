@extends('layouts.app')

@push('styles')
@vite('resources/css/faq.css')
@endpush

@section('content')
<section class="faq-header">
  <h1>Frequently Asked Questions</h1>
</section>

<section class="faq-container">
  <div class="faq-box">

    @php
    $faqs = [
      ['question' => 'Who is eligible for this program?', 'answer' => 'Any Degree/Branch/BE/Mtech final year, Passed outs, individuals, Employees are eligible for this program.'],
      ['question' => 'What is the duration of the program?'],
      ['question' => 'Do I get the assured placement?'],
      ['question' => 'What is the basic academic percentage required to enroll for the course?'],
      ['question' => 'What is the execution plan of the program?'],
      ['question' => 'Can we take this course online?'],
      ['question' => 'I am already employed, will I be eligible for the program?'],
      ['question' => 'What if I miss the session due to an emergency?'],
      ['question' => 'Can we take this course online?'],
      ['question' => 'Do you provide any certificate after the program?'],
      ['question' => 'Have suggestions for us?'],
    ];
    @endphp

    @foreach($faqs as $faq)
      <div class="faq-item">
        <input type="checkbox" id="faq-{{ $loop->index }}" class="faq-toggle">
        <label class="faq-question" for="faq-{{ $loop->index }}">
          {{ $faq['question'] }}
        </label>
        @if(isset($faq['answer']))
        <div class="faq-answer">
          <p>{{ $faq['answer'] }}</p>
        </div>
        @endif
      </div>
    @endforeach

  </div>
</section>
@endsection
