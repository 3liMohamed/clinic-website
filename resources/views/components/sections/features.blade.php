@if ($features->isNotEmpty())

    <!-- Start Feautes -->
    <section class="Feautes section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>@t('sections', 'features.title')</h2>
                        <img src="@galleryImage('sections:sections_image')" alt="@galleryTitle('sections:sections_image')" />
                        <p>@t('sections', 'features.description')</p>
                    </div>
                </div>
            </div>
            <div class="row">

                @foreach ($features as $feature)
                    <div class="col-lg-4 col-12">
                        <!-- Start Single features -->
                        <div @class(['single-features', 'first' => $loop->first, 'last' => $loop->last])>
                            <div class="signle-icon">
                                <i class="{{ $feature->icon }}"></i>
                            </div>
                            <h3>{{ $feature->title }}</h3>
                            <p>
                                {{ $feature->description }}
                            </p>
                        </div>
                        <!-- End Single features -->
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    <!--/ End Feautes -->
@endif
