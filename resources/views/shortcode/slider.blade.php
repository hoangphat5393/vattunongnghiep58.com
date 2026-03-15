@php extract($data) @endphp

@php $sliders = \App\Models\Slider::where('slider_id', $id)->get(); @endphp
@if (count($sliders) > 0)
    <div class="owl-carousel owl-nav-top-left" data-nav-arrow="{{ $arrow ?? 'true' }}" data-animateOut="fadeOut" data-nav-dots="{{ $dots ?? 'false' }}" data-items="{{ $items ?? '3' }}" data-md-items="{{ $items > 2 ? $items - 1 : '3' }}" data-sm-items="{{ $items > 2 ? $items - 2 : '3' }}" data-xs-items="3"
        data-xx-items="2" data-space="10">
        @foreach ($sliders as $item)
            <div class="item">
                <a href="{{ $item->link ?? 'javascript:;' }}" title="{{ $item->name }}">
                    <img class="img-fluid" src="{{ asset($item->src) }}" alt="{{ $item->name }}">
                </a>
            </div>
        @endforeach
    </div>
@endif
