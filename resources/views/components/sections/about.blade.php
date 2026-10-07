@if ($about)
    <!-- Start About Area -->
    <section class="about-area section">
        <div class="container-fluid p-0">
            <div class="row m-0">
                <div class="col-lg-6 col-md-12 p-0">
                    <div class="about-image" style="background-image: url({{ $about->image_url }})"></div>
                </div>
                <div class="col-lg-6 col-md-12 p-0">
                    <div class="about-content">
                        <span>{{ $about->sub_title }}</span>
                        <h2>{{ $about->title }}</h2>
                        <p>{{ $about->description }}</p>
                        <ul>
                            @foreach ($about->items as $item)
                                <li>
                                    <i class="{{ $item->icon }}"></i>{{ $item->text }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End About Area -->
@endif
