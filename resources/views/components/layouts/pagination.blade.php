@if ($paginator->hasPages())
    <!-- Pagination -->
    <div class="pagination">
        <ul class="pagination-list">

            {{-- Previous --}}
            <li>
                <a href="{{ $paginator->previousPageUrl() ?? '#' }}">
                    <i class="icofont-rounded-left"></i>
                </a>
            </li>

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li>
                        <span>{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li @class(['active' => $page == $paginator->currentPage()])>
                            <a href="{{ $url }}">
                                {{ $page }}
                            </a>
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            <li>
                <a href="{{ $paginator->nextPageUrl() ?? '#' }}">
                    <i class="icofont-rounded-right"></i>
                </a>
            </li>

        </ul>
    </div>
    <!--/ End Pagination -->
@endif
