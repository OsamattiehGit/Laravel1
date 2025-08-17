@props(['courseContentDetails'])

@if($courseContentDetails && is_array($courseContentDetails) && count($courseContentDetails) > 0)
    <div class="course-content-details">
        <h4 class="mb-3">Course Content</h4>

        @foreach($courseContentDetails as $section)
            @if(isset($section['section']) && !empty($section['section']))
                <div class="content-section mb-4">
                    <h5 class="section-title mb-3">{{ $section['section'] }}</h5>

                    @if(isset($section['items']) && is_array($section['items']) && count($section['items']) > 0)
                        <div class="content-items">
                            @foreach($section['items'] as $item)
                                @if(isset($item['type']) && isset($item['value']) && !empty($item['value']))
                                    <div class="content-item mb-3">
                                        @switch($item['type'])
                                            @case('text')
                                                <div class="text-content">
                                                    <p class="mb-0">{{ $item['value'] }}</p>
                                                </div>
                                                @break

                                            @case('image')
                                                <div class="image-content">
                                                    <img src="{{ Storage::url($item['value']) }}"
                                                         alt="Course content image"
                                                         class="img-fluid rounded"
                                                         style="max-width: 100%; height: auto;">
                                                </div>
                                                @break

                                            @case('video')
                                                <div class="video-content">
                                                    <video controls class="w-100 rounded">
                                                        <source src="{{ Storage::url($item['value']) }}" type="video/mp4">
                                                        <source src="{{ Storage::url($item['value']) }}" type="video/webm">
                                                        <source src="{{ Storage::url($item['value']) }}" type="video/ogg">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                                @break

                                            @default
                                                <div class="text-content">
                                                    <p class="mb-0">{{ $item['value'] }}</p>
                                                </div>
                                        @endswitch
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
@endif
