<!-- Service Details -->
<div class="service-details-area section">
    <div class="container">
        <div class="services-details-img">
            <img src="{{ $entity->image_url }}" alt="{{ $entity->title }}" />
            <h2>{{ $entity->title }}</h2>

            {!! preg_replace(
                '/<blockquote[^>]*>\s*<p>(.*?)<\/p>\s*<\/blockquote>/is',
                '<blockquote class="overlay"><i class="icofont-quote-left"></i>$1</blockquote>',
                $entity->content,
            ) !!}

        </div>

    </div>
</div>
<!-- End Service Details -->
