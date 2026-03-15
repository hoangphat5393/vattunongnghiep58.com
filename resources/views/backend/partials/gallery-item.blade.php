@php
    $image = $gallery ?? '';
    $index = $index ?? 0;
@endphp
<div class="group_item_images mb-2">
    <div class="inside d-flex">
        <div class="icon_change_postion"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
        <div class="image-view me-2">
            @if ($image)
                <img class="gallery-view{{ $index }}" src="{{ asset($image) }}" style="height: 38px;">
            @else
                <img class="gallery-view{{ $index }}" src="{{ asset('assets/images/placeholder.png') }}" style="height: 38px;">
            @endif
        </div>
        <div class="input-group">
            <div class="input-group">
                <input type="text" class="form-control" name="gallery[]" id="gallery-input{{ $index }}" value="{{ $image }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary ckfinder-popup" type="button" id="gallery-item{{ $index }}" data-show="gallery-view{{ $index }}" data="gallery-input{{ $index }}">Upload</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--group_item_images-->
