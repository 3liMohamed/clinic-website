@if ($faqs->isNotEmpty())
    <!-- Start Faq -->
    <section class="faq-area section">
        <div class="container">

            @foreach ($faqs as $categoryName => $categoryFaqs)
                <div class="row faq-wrap">

                    <div class="col-lg-12">

                        <div class="faq-head">
                            @if ($categoryName)
                                <h2>{{ $categoryName }}</h2>
                            @else
                                <div class="faq-divider"></div>
                            @endif
                        </div>

                        <div class="faq-item">
                            <ul class="accordion">

                                @foreach ($categoryFaqs as $faq)
                                    <li class="wow fadeInUp" data-wow-delay="{{ 0.3 + $loop->index * 0.1 }}s">
                                        <a>{{ $faq->question }}</a>

                                        <p>
                                            {{ $faq->answer }}
                                        </p>
                                    </li>
                                @endforeach

                            </ul>
                        </div>

                    </div>

                </div>
            @endforeach

        </div>
    </section>
    <!-- End Faq -->
@endif
