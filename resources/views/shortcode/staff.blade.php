@php extract($data) @endphp
@php
    $sliders = \App\Models\Slider::where('id', $id)->first();
@endphp
@empty(!$sliders)
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <h3 class="text-center fs-1">
                    {{ $sliders->name }}
                </h3>
            </div>
            @foreach ($sliders->children as $item)
                <div class="col-md-4">
                    <img class="img-fluid w-100 mb-3" src="{{ get_image($item->image) }}" alt="{{ $item->name }}">
                    <div>
                        {!! htmlspecialchars_decode($item->description) !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endempty
