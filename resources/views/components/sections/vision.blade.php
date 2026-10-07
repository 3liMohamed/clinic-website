@if ($visions->isNotEmpty())
    <!-- Start Our Vision Area -->
    <section class="our-vision-area ptb-100 pt-0">
        <div class="container">
            <div class="row">
                @foreach ($visions as $vision)
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-vision-box">
                            <div class="icon">
                                <i class="{{ $vision->icon }}"></i>
                            </div>
                            <h3>{{ $vision->title }}</h3>
                            <p>{{ $vision->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- End Our Vision Area -->
@endif
