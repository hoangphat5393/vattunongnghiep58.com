@php
    $collection = \App\Models\Slider::where('status', 0)
        ->where('slider_id', 59)
        ->orderBy('sort', 'asc')
        ->get();
@endphp
<div class="container-fluid collection-block py-5">
    <div class="bg-collection"></div>
    <div class="container py-5 my-5">
        <div class="section-title text-center mx-auto" style="max-width: 500px">
            <p>BỘ SƯU TẬP</p>
            <h6>MÀU SƠN ĐẸP VÀ ỨNG DỤNG</h6>
        </div>
        <div class="line-animation">
            <div class="line"></div>
        </div>
        <div class="content-collection text-center mx-auto" style="max-width: 950px">
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </p>
        </div>
        <div class="row g-4">
            @empty(!$collection)
                @foreach ($collection as $item)
                    <div class="col-md-4">
                        <div class="item-collection">
                            <a href="{{ $item->link }}" title="{{ $item->name }}">
                                <div class="thumb">
                                    <img src="{{ $item->src }}" />
                                </div>
                                <div class="content">
                                    <p>{{ $item->name }}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            @endempty
        </div>
    </div>
</div>
