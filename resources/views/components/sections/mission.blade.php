@if ($mission)
    <!-- Start Our Mission Area -->
    <section class="our-mission-area ptb-100 pt-0">
        <div class="container-fluid p-0">
            <div class="row m-0">
                <div class="col-lg-6 col-md-12 p-0">
                    <div class="our-mission-content">
                        <span class="sub-title">{{ $mission->sub_title }}</span>
                        <h2>{{ $mission->title }}</h2>
                        <p>{{ $mission->description }}</p>

                        @if ($mission->items->isNotEmpty())
                            <ul>
                                @foreach ($mission->items as $item)
                                    <li>
                                        <div class="icon"> <i class="{{ $item->icon }}"></i> </div> <span>
                                            {{ $item->title }} </span> {{ $item->description }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 p-0">
                    <div class="our-mission-image" style="background-image: url('{{ $mission->image_url }}')"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Our Mission Area -->
@endif
