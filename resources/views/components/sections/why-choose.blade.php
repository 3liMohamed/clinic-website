@if ($whyChoose)
    <!-- Start Why choose -->
    <section class="why-choose section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>@t('sections', 'why_choose.title')</h2>
                        <img src="@galleryImage('sections:sections_image')" alt="@galleryTitle('sections:sections_image')" />
                        <p>@t('sections', 'why_choose.description')</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-12">
                    <!-- Start Choose Left -->
                    <div class="choose-left">
                        <h3>{{ $whyChoose->title }}</h3>
                        {!! $whyChoose->content !!}
                        @if ($whyChoose->items->isNotEmpty())
                            <div class="row">
                                <div class="col-lg-6">
                                    <ul class="list">
                                        @foreach ($whyChoose->items()->left()->get() as $item)
                                            <li>
                                                <i class="fa fa-caret-right"></i>
                                                {{ $item->text }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-lg-6">
                                    <ul class="list">
                                        @foreach ($whyChoose->items()->right()->get() as $item)
                                            <li>
                                                <i class="fa fa-caret-right"></i>
                                                {{ $item->text }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                    </div>
                    <!-- End Choose Left -->
                </div>
                <div class="col-lg-6 col-12">
                    <!-- Start Choose Rights -->
                    <div class="choose-right" style="background-image: url('{{ $whyChoose->image_url }}')">
                        @if ($whyChoose->video_url !== '#')
                            <div class="video-image">
                                <!-- Video Animation -->
                                <div class="promo-video">
                                    <div class="waves-block">
                                        <div class="waves wave-1"></div>
                                        <div class="waves wave-2"></div>
                                        <div class="waves wave-3"></div>
                                    </div>
                                </div>
                                <!--/ End Video Animation -->
                                <a href="{{ $whyChoose->video_url }}" class="video video-popup mfp-iframe"><i
                                        class="fa fa-play"></i></a>
                            </div>
                        @endif
                    </div>
                    <!-- End Choose Rights -->
                </div>
            </div>
        </div>
    </section>
    <!--/ End Why choose -->
@endif
