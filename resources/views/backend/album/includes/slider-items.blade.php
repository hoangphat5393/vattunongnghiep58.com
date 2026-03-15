@foreach ($sliders as $slider)
    <div class="slider-item slider-{{ $slider->id }} form-group row mb-2 pb-2 border-bottom">
        <input type="hidden" name="slider[]" value="{{ $slider->id }}">
        <div class="col-md-3 d-flex align-items-center">
            <div class="icon_change_postion me-2"><i class="fa fa-sort"></i></div>
            <img src="{{ get_image($slider->image) }}" alt="" style="height: 50px;">
        </div>
        <div class="col-md-3">
            {{-- {{ $slider->sub_name }}
            <br> --}}
            {{ $slider->name }}
        </div>

        <div class="col-md-3">
            {{ $slider->link_name }}
            <br>
            <a href="{{ $slider->link }}">{{ $slider->link }}</a>
        </div>
        <div class="col-md-3 d-flex align-items-center">
            <button type="button" class="btn btn-sm btn-info edit-slider" data="{{ $slider->id }}" data-parent="{{ $slider->slider_id }}">Edit</button>
            <button type="button" class="btn btn-sm btn-danger delete-slider ms-2" data="{{ $slider->id }}">Delete</button>
        </div>
    </div>
@endforeach
