{{-- <ul class="pagination pagination-sm m-0 float-end">
    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
</ul> --}}

@php
    $move_to = $move_to ?? '';
@endphp

@if ($paginator->hasPages())
    {{-- <div class="card-footer clearfix"> --}}
    <div class="card-footer">
        <ul class="pagination pagination-sm m-0 float-end">
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
    </div>
@endif
