@if ($blogs->isNotEmpty())
    <!-- Start Blog Area -->
    <section class="blog section" id="blog">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>@t('sections', 'blogs.title')</h2>
                        <img src="@galleryImage('sections:sections_image')" alt="@galleryTitle('sections:sections_image')" />
                        <p>@t('sections', 'blogs.description')</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6 col-12">
                        <!-- Single Blog -->
                        <div class="single-news">
                            <div class="news-head">
                                <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" />
                            </div>
                            <div class="news-body">
                                <div class="news-content">
                                    <div class="date">{{ $blog->published_at->format('d M, Y') }}</div>
                                    <h2>
                                        <a
                                            @if ($blog->slug !== null) href="@route('blog.show', $blog->slug)" @else aria-disabled="true" @endif>{{ $blog->title }}</a>
                                    </h2>
                                    <p class="text">
                                        {{ $blog->description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Blog -->
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    <!-- End Blog Area -->
@endif
