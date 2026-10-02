
<!-- Single News -->
<section class="news-single section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="single-main">
                            <!-- News Head -->
                            <div class="news-head">
                                <img src="{{ $entity->image_url }}" alt="{{ $entity->title }}" />
                            </div>
                            <!-- News Title -->
                            <h1 class="news-title">
                                <a aria-disabled="true">{{ $entity->title }}</a>
                            </h1>
                            <!-- Meta -->
                            <div class="meta">
                                <div class="meta-left">
                                    <span class="author"><a aria-disabled="true"><img
                                                src="{{ $entity->user->getFilamentAvatarUrl() }}"
                                                alt="{{ $entity->user->name }}" />{{ $entity->user->name }}</a></span>
                                    <span class="date"><i
                                            class="fa fa-clock-o"></i>{{ $entity->published_at->format('d M Y') }}</span>
                                </div>
                            </div>
                            <!-- News Text -->
                            <div class="news-text" style="--blockquote-bg: url('@galleryImage('blog:blockqoute_bg')');">
                                {!! str_replace('<blockquote>', '<blockquote class="overlay">', $entity->content) !!}

                            </div>
                            <div class="blog-bottom">
                                <!-- Next Prev -->
                                <ul class="prev-next">
                                    @if ($entity->previous)
                                        <li class="prev">
                                            <a href="{{ route('blog.show', $entity->previous->slug) }}">
                                                <i class="fa fa-angle-double-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    @if ($entity->next)
                                        <li class="next">
                                            <a href="{{ route('blog.show', $entity->next->slug) }}">
                                                <i class="fa fa-angle-double-right"></i>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                                <!--/ End Next Prev -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ End Single News -->
