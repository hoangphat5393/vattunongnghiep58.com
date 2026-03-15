@php
    $move_to = $move_to ?? '';
@endphp
@if ($paginator->hasPages())
    <ul class="pagination justify-content-center">
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">«</span>
            </li>
        @else
            <li class="page-item">
                <a href="{{ $paginator->previousPageUrl() . $move_to }}" rel="prev" class="page-link">«</a>
            </li>
        @endif
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled">
                    <span class="page-link">{{ $element }}</span>
                </li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <a href="{{ $url . $move_to }}" class="page-link">{{ $page }}</a>
                        </li>
                    @else
                        <li class="page-item">
                            <a href="{{ $url . $move_to }}" class="page-link">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a href="{{ $paginator->nextPageUrl() . $move_to }}" rel="next" class="page-link">»</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">»</span>
            </li>
        @endif
    </ul>
@endif
