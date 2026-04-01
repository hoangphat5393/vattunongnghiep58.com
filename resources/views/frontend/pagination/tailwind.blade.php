@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Phân trang" class="mt-10">
        <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-between">
            <p class="text-sm text-gray-600">
                @if ($paginator->firstItem())
                    Hiển thị
                    <span class="font-semibold text-gray-900">{{ $paginator->firstItem() }}</span>
                    –
                    <span class="font-semibold text-gray-900">{{ $paginator->lastItem() }}</span>
                    trong tổng số
                    <span class="font-semibold text-gray-900">{{ $paginator->total() }}</span>
                    sản phẩm
                @else
                    <span class="font-semibold text-gray-900">{{ $paginator->count() }}</span> sản phẩm
                @endif
            </p>

            <div class="inline-flex flex-wrap items-center justify-center gap-1 rounded-xl border border-leaf-100 bg-white p-1 shadow-sm">
                @if ($paginator->onFirstPage())
                    <span class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-400" aria-disabled="true">«</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg text-sm font-semibold text-leaf-700 transition hover:bg-leaf-50" aria-label="Trang trước">«</a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="inline-flex min-h-10 items-center px-2 text-sm text-gray-400">{{ $element }}</span>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg bg-leaf-600 text-sm font-bold text-white shadow-sm" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg text-sm font-semibold text-gray-700 transition hover:bg-leaf-50 hover:text-leaf-800">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg text-sm font-semibold text-leaf-700 transition hover:bg-leaf-50" aria-label="Trang sau">»</a>
                @else
                    <span class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-400" aria-disabled="true">»</span>
                @endif
            </div>
        </div>
    </nav>
@endif
