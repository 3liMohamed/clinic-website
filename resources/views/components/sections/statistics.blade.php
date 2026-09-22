@if ($statistics->isNotEmpty())
    <!-- Start Fun-facts -->
    <div id="fun-facts" class="fun-facts section overlay">
        <div class="container">
            <div class="row">
                @foreach ($statistics as $statistic)
                    <div class="col-lg-3 col-md-6 col-12">
                        <!-- Start Single Fun -->
                        <div class="single-fun">
                            <i class="{{ $statistic->icon }}"></i>
                            <div class="content">
                                <span class="counter">{{ $statistic->count }}</span>
                                <p>{{ $statistic->title }}</p>
                            </div>
                        </div>
                        <!-- End Single Fun -->
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!--/ End Fun-facts -->
@endif
