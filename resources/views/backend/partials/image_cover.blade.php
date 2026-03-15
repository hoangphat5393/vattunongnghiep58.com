<div class="card">
    <div class="card-header">
        <h3 class="card-title">Image Cover</h3>
    </div> <!-- /.card-header -->
    <div class="card-body">
        <div class="form-group">
            <label for="thumbnail_file">File input</label>
            <div class="input-group">
                <div class="custom-file">
                    <input type="text" name="cover" class="custom-file-link form-control" id="cover" value="{{$cover ?? ''}}">
                    <label class="custom-file-label custom-file-label-thumb ckfinder-popup" id="cover-img" data-show="cover_view" data="cover"></label>
                </div>
            </div>
            <div class="demo-img" style="padding-top: 15px;">
                @if(isset($cover) && $cover != "")
                    <img class="cover_view" src="{{asset($cover)}}">
                @else
                    <img class="cover_view" src="{{asset('assets/images/placeholder.png')}}">
                @endif
            </div>
        </div>
    </div> <!-- /.card-body -->
</div><!-- /.card -->
