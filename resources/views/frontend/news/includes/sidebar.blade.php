{{-- Sidebar --}}
<div class="position-sticky ms-lg-auto blog-sidebar">
    <div class="offcanvas-header align-items-center shadow-sm d-none">
        <h2 class="h5 mb-0">Sidebar</h2>
        <button class="btn-close ms-auto" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body py-grid-gutter px-lg-3" data-simplebar data-simplebar-auto-hide="true">

        @if ($category_child->count())
            <!-- Categories-->
            <div class="widget widget-links mb-grid-gutter pb-grid-gutter mx-lg-2">
                <h4 class="sidebar-title mb-4">CATEGORY</h4>
                <ul class="list-unstyled">
                    @foreach ($category_child as $child)
                        @if ($child->category()->count())
                            <li class="mb-2">
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="true"></button>
                                    {{ $child->name }}
                                </div>
                                <div class="collapse show" id="account-collapse" style="">
                                    <ul class="btn-toggle-nav list-unstyled">
                                        @foreach ($child->category as $item)
                                            <li class="ms-4"><a href="{{ route('news', $item->slug) }}" class="link-dark d-inline-flex text-decoration-none rounded">{{ $item->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                            <li class="mb-2">
                                <a href="{{ route('news', $child->slug) }}" class="link-dark d-inline-flex text-decoration-none rounded">{{ $child->name }}</a>
                            </li>
                        @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- LATEST POSTS --}}
        @if ($latest_news->count())
            <!-- Trending posts-->
            <div class="widget new_posts mt-4 mx-lg-2">
                <h4 class="sidebar-title mb-4">LATEST NEWS</h4>
                @foreach ($latest_news as $news)
                    <div class="d-flex align-items-center mb-4">
                        <a class="shrink-0" href="{{ route('news', $news->slug) }}">
                            <img class="img-fluid" src="{{ $news->image }}" width="64" alt="{{ $news->name }}">
                        </a>
                        <div class="ps-2">
                            <h6 class="blog-entry-title mb-0">
                                <a href="{{ route('news', $news->slug) }}">{{ $news->name }}</a>
                            </h6>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        {{-- END LATEST POSTS --}}

    </div>
</div>
