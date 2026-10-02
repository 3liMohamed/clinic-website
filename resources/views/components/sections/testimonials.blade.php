@if ($testimonials->isNotEmpty())
    <!-- Start Testimonials -->
    <section class="section testimonials overlay" data-stellar-background-ratio="0.5"
    style="background-image: url('@galleryImage('sections:testimonials_bg')')">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>@t('sections', 'testimonials.title')</h2>
                        <img src="@galleryImage('sections:sections_image2')" alt="@galleryTitle('sections:sections_image')" />
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-lg-12 col-12">
                    <div class="owl-carousel testimonial-slider">
                        @foreach ($testimonials as $testimonial)
                            <!-- Start Single Testimonial -->
                            <div class="single-testimonial">
                                <img
                                    src="{{ $testimonial->avatar_url }}"
                                    alt="{{ $testimonial->name }}"
                                />

                                <p>
                                    {{ $testimonial->content }}
                                </p>

                                <h4 class="name">
                                    {{ $testimonial->name }}
                                </h4>
                            </div>
                            <!-- End Single Testimonial -->
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ End Testimonials -->
@endif
