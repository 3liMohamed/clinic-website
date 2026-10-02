@if ($pricings->isNotEmpty())
    <!-- Pricing Table -->
    <section class="pricing-table section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>@t('sections', 'pricings.title')</h2>
                        <img src="@galleryImage('sections:sections_image')" alt="@galleryTitle('sections:sections_image')" />
                        <p>@t('sections', 'pricings.description')</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($pricings as $pricing)
                    <!-- Single Table -->
                    <div class="col-lg-4 col-md-12 col-12">
                        <div class="single-table">
                            <!-- Table Head -->
                            <div class="table-head">
                                <div class="icon">
                                    <i class="{{ $pricing->icon }}"></i>
                                </div>
                                <h4 class="title">{{ $pricing->title }}</h4>
                                <div class="price">
                                    <p class="amount">{{ number_format($pricing->price, 2) }}
                                        <span>
                                            / {{ $pricing->price_suffix->getLabel() }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <!-- Table List -->
                            <ul class="table-list">

                                @foreach ($pricing->features as $feature)
                                    <li @class(['cross' => !$feature->is_available])>

                                        <i
                                            class="{{ $feature->is_available ? 'icofont icofont-ui-check' : 'icofont icofont-ui-close' }}">
                                        </i>

                                        {{ $feature->text }}

                                    </li>
                                @endforeach
                            </ul>
                            <div class="table-bottom">
                                @if ($pricing->button_url !== '#')
                                    <a class="btn" href="{{ $pricing->button_url }}">{{ $pricing->button_text }}</a>
                                @endif
                            </div>
                            <!-- Table Bottom -->
                        </div>
                    </div>
                    <!-- End Single Table-->
                @endforeach

            </div>
        </div>
    </section>
    <!--/ End Pricing Table -->
@endif
