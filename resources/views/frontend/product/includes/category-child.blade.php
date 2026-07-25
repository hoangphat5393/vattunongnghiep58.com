@if ($children->exists())
    <div class="nav-scroll mb-5">
        <ul class="nav nav-pills nav-product-tabs" id="pills-tab" role="tablist">
            @php $i = 0; @endphp
            @foreach ($children->get() as $item)
                <li class="nav-item">
                    <a class="nav-link {{ $i == 0 ? 'active' : '' }}" data-bs-toggle="tab" href="#tab-child-{{ $i }}" role="tab">
                        <img src="{{ get_image($item->icon) }}" />
                        <p>{{ $item->name }}</p>
                    </a>
                </li>
                @php $i++; @endphp
            @endforeach
        </ul>
    </div>

    @php $i = 0; @endphp
    <div class="tab-content">
        @foreach ($children->get() as $item)
            <div class="tab-pane fade  {{ $i == 0 ? 'show active' : '' }}" id="tab-child-{{ $i }}" role="tabpanel" aria-bs-labelledby="pills-out-tab">
                <div class="info-product">
                    <h5>{{ $item->name }}</h5>
                    {!! htmlspecialchars_decode($item->content) !!}

                    @if ($item->products()->exists())
                        <div class="product-outdoor">
                            <h5>Một số sản phẩm đặc trưng của {{ $item->name }}:</h5>
                            <div class="row g-4">
                                @foreach ($item->products as $item2)
                                    <div class="col-12 col-lg-4">
                                        <div class="item-product">
                                            <a href="{{ route('product.detail', [$item2->slug, $item2->id]) }}">
                                                <div class="thumb">
                                                    <img class="img-fluid" src="{{ get_image($item2->image) }}" />
                                                    <p style="top:{{ $item2->text_position }}">{{ $item2->image_name }}</p>
                                                </div>
                                                <div class="content">
                                                    <h6>{{ $item2->name }}</h6>
                                                    <div class="content--truncate" data-bs-toggle="popover" data-bs-html="true" data-bs-trigger="hover focus" data-bs-content="{{ htmlspecialchars_decode($item2->description) }}">
                                                        {!! htmlspecialchars_decode($item2->description) !!}
                                                    </div>
                                                </div>

                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @php $i++; @endphp
        @endforeach


    </div>
@endif
