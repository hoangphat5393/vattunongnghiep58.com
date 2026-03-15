@php
    $category = \App\Models\Frontend\Category::where(['parent' => 0, 'status' => 1])->get();
    // dd($category);
@endphp

<div class="col-sm-12 col-md-6 col-md-12 mb-4 d-none d-lg-block">
    <div class="bg-title">
        <h2>DANH MỤC SẢN PHẨM</h2>
    </div>
    <div class="main-cat-content">
        @empty(!$category)
            <ul class="list-group list-group-flush">
                @foreach ($category as $item)
                    <li class="list-group-item">
                        <a class="" href="{{ route('product.category', $item->slug) }}" title="{{ $item->name }}">{{ $item->name }}</a>
                        @if ($item->children()->exists())
                            <ul class="list-group list-group-flush ms-3">
                                @foreach ($item->children as $item2)
                                    <li class="list-group-item">
                                        <a class="" href="{{ route('product.category', $item2->slug) }}" title="{{ $item2->name }}">{{ $item2->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                    </li>
                @endforeach
            </ul>
        @endempty
    </div>
</div>
