
@if ($clients->isNotEmpty())
    <!-- clients -->
    <div class="clients overlay" style="background-image: url('@galleryImage('sections:client_bg')')">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="owl-carousel clients-slider">
                        @foreach ($clients as $client)
                            <div class="single-clients">
                                <img src="{{ $client->image_url }}" alt="{{ $client->name }}" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/Ens clients -->
@endif
