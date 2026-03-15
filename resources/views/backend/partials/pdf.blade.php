<div class="card">
    <div class="card-header">
        <h5>{{ $title ?? 'PDF File' }}</h5>
    </div>
    <div class="card-body">
        <div class="input-group">
            <div class="input-group">
                <input type="text" class="form-control" name="{{ $name ?? 'image' }}" id="{{ $name ?? 'image' }}" value="{{ $image }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary ckfinder-popup" type="button" id="{{ $id ?? 'img' }}" data-show="{{ $id ?? 'img' }}_view" data="{{ $name ?? 'image' }}">Upload</button>
                </div>
            </div>
        </div>
        <div class="demo-img" style="padding-top: 10px;">
            @if ($image)
                <object data="{{ $image }}" type="application/pdf" width="100%" height="500px">
                    <p>
                        Unable to display PDF file.
                        <a href="{{ $image }}">Download</a> instead.
                    </p>
                </object>
            @endif
        </div>
    </div> <!-- /.card-body -->
</div><!-- /.card -->
