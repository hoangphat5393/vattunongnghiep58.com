@php
    $categoryMenu = Menu::getByName('Categories-product-home');
@endphp
@if ($categoryMenu)
    @foreach ($categoryMenu as $category)
        <section id="majors">
            <div class="container">
                <div class="box-tit-sec text-center">
                    <h3 class="tit-sec text-uppercase">{!! $category['label'] !!}</h3>
                </div>
                @if ($category['child'])
                    <div class="wrap-majors flex">
                        @foreach ($category['child'] as $item)
                            <div class="box-item-major">
                                <a href="" class="link-major"></a>
                                <img src="{{ $item['icon'] ? asset($item['icon']) : '' }}" alt="">
                                <div class="box-tit-item-major">
                                    <h4 class="tit-item-major">{{ $item['label'] }}</h4>
                                    <a href="{{ url($item['link']) }}">@lang('Xem thêm')</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </section>
    @endforeach
@endif
