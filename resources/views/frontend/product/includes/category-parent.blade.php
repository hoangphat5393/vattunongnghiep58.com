@if ($category->products()->exists())
    <div class="product-outdoor">
        <h5>Một số sản phẩm đặc trưng của {{ $category->name }}:</h5>
        <div class="row g-4">
            @foreach ($category->products as $item)
                <div class="col-12 col-lg-4">
                    <div class="item-product">
                        <a href="{{ route('product.detail', [$item->slug, $item->id]) }}">
                            <div class="thumb">
                                <img class="img-fluid" src="{{ get_image($item->image) }}" />
                                <p style="top:{{ $item->text_position }}">{{ $item->image_name }}</p>
                            </div>
                            <div class="content">
                                <h6>{{ $item->name }}</h6>
                                <div class="content--truncate">
                                    {!! htmlspecialchars_decode($item->description) !!}
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
