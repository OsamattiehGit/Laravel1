@extends('layouts.app')

@push('styles')
    @vite('resources/css/faq.css')
@endpush

@section('content')
<!-- FAQ Header -->
<section class="faq-header">
  <div class="container">
    <h1>Frequently Asked Questions</h1>
  </div>
</section>

<!-- FAQ Content -->
<section class="faq-container position-relative">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10 col-xl-8">
        <div class="faq-box">

          @php
          $faqs = [
            [
              'question' => 'Who is eligible for this program?',
              'answer' => 'Any Degree/Branch/BE/MTech final year students, graduates, individuals seeking a career upgrade, or working professionals are eligible for this program.'
            ],
            [
              'question' => 'What is the duration of the program?',
              'answer' => 'The program duration varies depending on the course you choose, typically ranging from 8 to 16 weeks, with both weekday and weekend options available.'
            ],
            [
              'question' => 'Do I get the assured placement?',
              'answer' => 'Yes, we offer 100% placement assistance through our dedicated career services team. Placement is based on performance, course completion, and meeting eligibility criteria.'
            ],
            [
              'question' => 'What is the basic academic percentage required to enroll for the course?',
              'answer' => 'There is no strict percentage requirement. However, a minimum of 50% in your academic background is preferred for placement assistance.'
            ],
            [
              'question' => 'What is the execution plan of the program?',
              'answer' => 'The program follows a structured learning path that includes instructor-led sessions, hands-on projects, assessments, mock interviews, and real-time mentorship.'
            ],
            [
              'question' => 'Can we take this course online?',
              'answer' => 'Yes, all our programs are available in both online and offline modes. You can choose the format that suits your schedule and location.'
            ],
            [
              'question' => 'I am already employed, will I be eligible for the program?',
              'answer' => 'Absolutely. The program is designed for working professionals as well. Flexible learning options like weekend and evening batches are available.'
            ],
            [
              'question' => 'What if I miss the session due to an emergency?',
              'answer' => 'If you miss a session, you will receive the class recording and materials. You can also attend the same session in another batch based on availability.'
            ],
            [
              'question' => 'Do you provide any certificate after the program?',
              'answer' => 'Yes, upon successful completion of the program, you will receive a course completion certificate that is recognized by hiring partners.'
            ],
            [
              'question' => 'Have suggestions for us?',
              'answer' => 'We value your feedback! Please feel free to share your suggestions through our contact form or email. Your input helps us improve.'
            ],
          ];
          @endphp

          @foreach($faqs as $index => $faq)
            <div class="faq-item">
              <input type="checkbox" id="faq-{{ $index }}" class="faq-toggle">
              <label class="faq-question" for="faq-{{ $index }}">
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
      </div>
    </div>
  </div>
</section>
@endsection
