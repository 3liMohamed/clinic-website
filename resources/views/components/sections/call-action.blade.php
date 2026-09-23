@if ($callAction)
    <!-- Start Call to action -->
    <section class="call-action overlay" data-stellar-background-ratio="0.5"
        style="background-image: url('{{ $callAction->image_url }}')">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="content">
                        <h2>{{ $callAction->title }}</h2>
                        <p>{{ $callAction->description }}</p>

                        <div class="button">
                            @if ($callAction->primary_button_url !== '#')
                                <a href="{{ $callAction->primary_button_url }}"
                                    class="btn">{{ $callAction->primary_button_text }}</a>
                            @endif
                            @if ($callAction->secondary_button_url !== '#')
                                <a href="{{ $callAction->secondary_button_url }}"
                                    class="btn second">{{ $callAction->secondary_button_text }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ End Call to action -->
@endif
