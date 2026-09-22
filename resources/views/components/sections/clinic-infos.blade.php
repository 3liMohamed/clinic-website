@if ($infos->isNotEmpty())
    <!-- Start Schedule Area -->
    <section class="schedule">
        <div class="container">
            <div class="schedule-inner">
                <div class="row">

                    @foreach ($infos as $info)
                        <div class="col-lg-4 col-md-6 col-12">
                            <!-- single-schedule -->
                            <div class="single-schedule first">
                                <div class="inner">
                                    <div class="icon">
                                        <i class="{{ $info->icon }}"></i>
                                    </div>
                                    <div class="single-content">
                                        <span>{{ $info->sub_title }}</span>
                                        <h4>{{ $info->title }}</h4>
                                        <p>{{ $info->description }}</p>
                                        @if ($info->link_url !== '#')

                                        <a href="{{ $info->link_url }}">{{ $info->text }}<i class="fa fa-long-arrow-right"></i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
    <!--/End Start schedule Area -->
@endif
