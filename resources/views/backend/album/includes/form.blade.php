@php
    if (isset($album_item)) {
        extract($album_item->getAttributes());
    }
@endphp

<form id="form-editSlider" action="" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="album_id" value="{{ $parent ?? 0 }}">

    {{-- Tab Lang --}}
    <ul class="nav nav-tabs d-none" id="tabLang" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="vi-tab" data-bs-toggle="tab" href="#vi-child" role="tab" aria-controls="vi-child" aria-selected="true">@lang('admin.Vietnamese')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="en-tab" data-bs-toggle="tab" href="#en-child" role="tab" aria-controls="en-child" aria-selected="false">@lang('admin.English')</a>
        </li>
    </ul>

    {{-- VI --}}
    <div class="tab-content">
        <div class="tab-pane fade show active" id="vi-child" role="tabpanel" aria-labelledby="vi-tab">

            <div class="form-group">
                <label for="name">@lang('admin.Image')</label>
                <div class="inserIMG">
                    <input type="hidden" name="image" id="src-img-vi" value="{{ $image ?? '' }}">
                    <img src="{{ get_image($image) }}" id="show-img-vi" class="show-img src-img ckfinder-popup" data-show="show-img-vi" data="src-img-vi" width="200">
                    {{-- @if (isset($image) && $image != '')
                        <img src="{{ get_image($image) }}" id="show-img-vi" class="show-img src-img ckfinder-popup" data-show="show-img-vi" data="src-img-vi" width="200">
                    @else
                        <img src="{{ asset('assets/images/placeholder.png') }}" id="show-img-vi" class="src-img ckfinder-popup" data-show="show-img-vi" data="src-img-vi" width="200">
                    @endif --}}
                    <span class="remove-icon ms-3">
                        <i class="fa-solid fa-xmark fa-lg"></i>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label for="link">@lang('admin.Link')</label>
                <input type="text" class="form-control" name="link" value="{{ $link ?? '#' }}">
            </div>

            <div class="form-group">
                <label for="link">@lang('admin.Link name')</label>
                <input type="text" class="form-control" name="link_name" value="{{ $link_name ?? '' }}">
            </div>

            <div class="form-group">
                <label for="name">@lang('admin.Title')</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $name ?? '' }}" placeholder="@lang('admin.Title')">
            </div>

            <div class="form-group">
                <label for="name">@lang('admin.Sub title')</label>
                <input type="text" class="form-control" id="sub_name" name="sub_name" value="{{ $sub_name ?? '' }}" placeholder="@lang('admin.Sub title')">
            </div>

            <div class="form-group">
                <label for="description">@lang('admin.Description')</label>
                <textarea id="description" class="form-control" name="description">{!! $description ?? '' !!}</textarea>
            </div>
        </div>

        {{-- EN --}}
        <div class="tab-pane fade" id="en-child" role="tabpanel" aria-labelledby="en-tab">

            <div class="form-group">
                <label for="name_en">@lang('admin.Image')</label>
                <div class="inserIMG">
                    <input type="hidden" name="image_en" id="src-img-en" value="{{ $image_en ?? '' }}">
                    @if (isset($image_en) && $image_en != '')
                        <img src="{{ get_image($image_en) }}" id="show-img-en" class="show-img src-img-en ckfinder-popup" data-show="show-img-en" data="src-img-en" width="200">
                    @else
                        <img src="{{ asset('assets/images/placeholder.png') }}" id="show-img-en" class="src-img-en ckfinder-popup" data-show="show-img-en" data="src-img-en" width="200">
                    @endif
                    <span class="remove-icon ms-3">
                        <i class="fa-solid fa-xmark fa-lg"></i>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label for="link">@lang('admin.Link')</label>
                <input type="text" class="form-control" name="link_en" value="{{ $link_en ?? '#' }}">
            </div>

            <div class="form-group">
                <label for="link">@lang('admin.Link name')</label>
                <input type="text" class="form-control" name="link_name_en" value="{{ $link_name_en ?? '' }}">
            </div>

            <div class="form-group">
                <label for="name_en">@lang('admin.Title')</label>
                <input type="text" class="form-control" id="name_en" name="name_en" placeholder="Sub title" value="{{ $name_en ?? '' }}">
            </div>

            <div class="form-group">
                <label for="name">@lang('admin.Sub title')</label>
                <input type="text" class="form-control" id="sub_name_en" name="sub_name_en" value="{{ $sub_name_en ?? '' }}" placeholder="@lang('admin.Sub title')">
            </div>

            <div class="form-group">
                <label for="description_en">@lang('admin.Description')</label>
                <textarea id="description_en" class="form-control" name="description_en">{!! $description_en ?? '' !!}</textarea>
            </div>
        </div>
    </div>

    {{-- Video --}}
    {{-- <div class="form-group">
        Video
        <input type="text" name="video_name" class="form-control" value="{{ $video_name ?? '' }}">
        <div class="inserIMG d-flex align-items-center">
            <input type="hidden" name="video" id="src-video" value="{{ $video ?? '' }}">
            @if (isset($video) && $video != '')
                <video height="240" controls>
                    <source src="{{ $slider->video }}">
                    Your browser does not support the video tag.
                </video>
            @else
                <img src="{{ asset('assets/images/placeholder.png') }}" id="show-video" class="src-video ckfinder-popup" data-show="show-video" data="src-video" width="200">
            @endif

            <span class="remove-icon ms-3">
                <i class="fa-solid fa-xmark fa-lg"></i>
            </span>
        </div>
    </div> --}}

    <div class="form-group">
        <label for="sort">@lang('admin.Sort')</label>
        <input id="sort" type="text" name="sort" class="form-control" value="{{ $sort ?? 0 }}">
    </div>
</form>
