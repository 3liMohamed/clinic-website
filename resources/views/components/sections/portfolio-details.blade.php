
    <!-- Start Portfolio Details Area -->
    <section class="pf-details section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="inner-content">
                        @if ($entity->gallery_urls !== null)
                            <div class="image-slider">
                                <div class="pf-details-slider">
                                    @foreach ($entity->gallery_urls as $image_url)
                                        <img src="{{ $image_url }}" alt="{{ $entity->title }}" />
                                    @endforeach
                                </div>
                            </div>
                            <div class="date">
                                <ul>
                                    <li><span>@lang('system.field.category') :</span> {{ $entity->category->name }}</li>
                                    <li><span>@lang('system.field.project_date') :</span> {{ $entity->project_date?->format('F d, Y') }}</li>
                                    <li><span>@lang('system.field.client') :</span> {{ $entity->client }}</li>
                                </ul>
                            </div>
                        @endif

                        <div class="body-text">
                            <h3>{{ $entity->title }}</h3>
                            {!! $entity->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Portfolio Details Area -->
