@if ($portfolios->isNotEmpty())
    <!-- Start portfolio -->
    <section class="portfolio section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>@t('sections', 'portfolio.title')</h2>
                        <img src="@galleryImage('sections:sections_image')" alt="@galleryTitle('sections:sections_image')" />
                        <p>@t('sections', 'portfolio.description')</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="owl-carousel portfolio-slider">
                        @foreach ($portfolios as $portfolio)
                            <div class="single-pf">
                                <img src="{{ $portfolio->cover_image_url }}" alt="{{ $portfolio->title }}" />
                                @if ($portfolio->slug !== null)
                                    <a href="@route('portfolio.show', $portfolio->slug)" class="btn">@t('sections', 'btn.view')</a>
                                @endif
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ End portfolio -->
@endif
