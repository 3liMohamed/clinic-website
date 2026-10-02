<!-- Breadcrumbs -->
<div class="breadcrumbs overlay" style="background-image: url('{{ $page->breadcrumb_image_url }}')">
    <div class="container">
        <div class="bread-inner">
            <div class="row">
                <div class="col-12">
                    <h2>{{ $page->breadcrumb_title }}</h2>
                    <ul class="bread-list">
                        @foreach ($page->breadcrumbItems as $item)
                            <li @class(['active' => $loop->last])>
                                @if ($item->url && !$loop->last)
                                    <a href="{{ $item->url }}">
                                        {{ $item->text }}
                                    </a>
                                @else
                                    {{ $item->text }}
                                @endif
                            </li>

                            @unless ($loop->last)
                                <li>
                                    <i class="icofont-simple-right"></i>
                                </li>
                            @endunless
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->
