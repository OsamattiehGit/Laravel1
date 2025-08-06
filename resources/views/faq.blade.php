@extends('layouts.app')

@push('styles')
<style>
/* Custom styles to match Figma design exactly */
.faq-header {
  background: #f3702b;
  color: white;
  text-align: center;
  padding: 3rem 1rem;
  border-bottom-left-radius: 50px;
  border-bottom-right-radius: 50px;
  margin-bottom: 3rem;
}

.faq-header h1 {
  font-size: 2.5rem;
  font-weight: 700;
  margin: 0;
  text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.faq-container {
  min-height: 60vh;
  background: linear-gradient(45deg, #f8f9fa 0%, #ffffff 100%);
  padding: 2rem 0 4rem 0;
}

.faq-box {
  background: white;
  border-radius: 25px;
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
  padding: 2.5rem;
  border: 1px solid #f0f0f0;
}

.faq-item {
  border-bottom: 1px solid #eee;
  padding: 1.5rem 0;
  transition: all 0.3s ease;
}

.faq-item:last-child {
  border-bottom: none;
}

.faq-item:hover {
  background-color: #fafafa;
  border-radius: 10px;
  padding-left: 1rem;
  padding-right: 1rem;
}

.faq-toggle {
  display: none;
}

.faq-question {
  font-size: 1.15rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  cursor: pointer;
  padding-left: 2.5rem;
  position: relative;
  color: #333;
  transition: color 0.3s ease;
  line-height: 1.5;
}

.faq-question:hover {
  color: #f3702b;
}

/* Plus/Minus icon */
.faq-question::before {
  content: "+";
  color: #f3702b;
  font-size: 1.8rem;
  font-weight: 700;
  position: absolute;
  left: 0;
  transition: all 0.3s ease;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(243, 112, 43, 0.1);
  border-radius: 50%;
  line-height: 1;
}

.faq-toggle:checked + .faq-question::before {
  content: "−";
  transform: rotate(180deg);
  background: rgba(243, 112, 43, 0.2);
}

.faq-answer {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.4s ease, padding 0.4s ease;
  font-size: 1rem;
  color: #666;
  margin-top: 0;
  padding-left: 2.5rem;
  padding-right: 1rem;
  line-height: 1.6;
}

.faq-toggle:checked + .faq-question + .faq-answer {
  max-height: 200px;
  padding-top: 1rem;
  padding-bottom: 0.5rem;
}

.faq-answer p {
  margin: 0;
}

/* Decorative elements */
.faq-container::before {
  content: '';
  position: absolute;
  left: -50px;
  top: 50%;
  width: 100px;
  height: 100px;
  background: rgba(243, 112, 43, 0.05);
  border-radius: 50px;
  transform: translateY(-50%);
  z-index: 1;
}

.faq-box {
  position: relative;
  z-index: 2;
}

@media (max-width: 768px) {
  .faq-header {
    padding: 2rem 1rem;
    border-bottom-left-radius: 30px;
    border-bottom-right-radius: 30px;
  }

  .faq-header h1 {
    font-size: 2rem;
  }

  .faq-box {
    padding: 1.5rem;
    border-radius: 20px;
  }

  .faq-question {
    font-size: 1rem;
    padding-left: 2rem;
  }

  .faq-answer {
    padding-left: 2rem;
  }
}
</style>
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
