<div class="mb-4 card card-warning card-outline">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ $title ?? 'Ảnh đại diện' }}</h5>
    </div>
    <div class="card-body">
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="{{ $name ?? 'image' }}" id="{{ $name ?? 'image' }}" value="{{ $image }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary ckfinder-popup" type="button" id="{{ $id ?? 'img' }}" data-show="{{ $id ?? 'img' }}_view" data="{{ $name ?? 'image' }}">Upload</button>
            </div>
        </div>
        <div class="demo-img text-center pt-2">
            <img class="{{ $id ?? 'img' }}_view img-fluid rounded" src="{{ get_image($image) }}" style="max-height: 200px;">
        </div>
    </div> <!-- /.card-body -->
</div><!-- /.card -->
