@if ($blogs->isNotEmpty())
    <!-- Single News -->
    <section class="blog grid section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        @foreach ($blogs as $blog)
                            <div class="col-lg-4 col-md-6 col-12">
                                <!-- Single Blog -->
                                <div class="single-news">
                                    <div class="news-head">
                                        <img src="{{ $blog->image_url }}" alt="#" />
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

                        <div class="col-12">
                            <!-- Pagination -->
                            {{ $blogs->links('components.layouts.pagination') }}
                            <!--/ End Pagination -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ End Single News -->
@endif
