@if ($slides->isNotEmpty())
    <!-- Slider Area -->
    <section class="slider">
        <div class="hero-slider">
            @foreach ($slides as $slide)
                <!-- Start Single Slider -->
                <div class="single-slider" style="background-image: url('{{ $slide->image_url }}')">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="text">
                                    <h1>
                                        <h1>{!! $slide->title !!}</h1>
                                    </h1>
                                    <p>
                                        {{ $slide->description }}
                                    </p>
                                    <div class="button">
                                        @if ($slide->primary_button_url !== '#')
                                            <a href="{{ $slide->primary_button_url }}" class="btn">{{ $slide->primary_button_text }}</a>
                                        @endif
                                        @if ($slide->secondary_button_url !== '#')
                                            <a href="{{ $slide->secondary_button_url }}" class="btn primary">{{ $slide->secondary_button_text }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Single Slider -->
            @endforeach
        </div>
    </section>
    <!--/ End Slider Area -->
@endif
