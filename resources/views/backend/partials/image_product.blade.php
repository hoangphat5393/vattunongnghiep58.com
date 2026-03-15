<div class="card">

    <div class="card-header">
        <h5>{{ $title ?? 'Image Thumbnail' }}</h5>
    </div>

    <div class="card-body">
        <div class="form-group mb-3">
            <label for="image_name" class="form-label">Tên in trên ảnh</label>
            <input type="text" name="image_name" id="image_name" class="form-control" value="{{ $image_name ?? '' }}">
        </div>
        <div class="form-group mb-3">
            <label for="text_position" class="form-label">Vị trí</label>
            <input type="text" name="text_position" id="text_position" class="form-control" value="{{ $text_position ?? '' }}">
        </div>

        <div class="input-group">
            <div class="input-group">
                <input type="text" class="form-control" name="{{ $name ?? 'image' }}" id="{{ $name ?? 'image' }}" value="{{ $image }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary ckfinder-popup" type="button" id="{{ $id ?? 'img' }}" data-show="{{ $id ?? 'img' }}_view" data="{{ $name ?? 'image' }}">Upload</button>
                </div>
            </div>
        </div>
        <div class="demo-img" style="padding-top: 10px;">
            <img class="{{ $id ?? 'img' }}_view" src="{{ get_image($image) }}">
        </div>
    </div> <!-- /.card-body -->
</div><!-- /.card -->
