@if ($services->isNotEmpty())
    <!-- Start service -->
    <section class="services section">
        <div class="container">
            <div class="row">
                @foreach ($services as $service)
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-service">
                            <i class="{{ $service->icon }}"></i>
                            <h4><a @if ($service->slug) href="@route('service.show', $service->slug)"
                                     @else aria-disabled="true" @endif>{{ $service->title }}</a></h4>
                            <p>{{ $service->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--/ End service -->
@endif
