<div class="card">
    <div class="card-header">
        <h3 class="card-title">Seo IMG</h3>
    </div> <!-- /.card-header -->
    <div class="card-body">
        <div class="form-group">
            <label for="thumbnail_file">File input</label>
            <div class="input-group">
                <div class="custom-file">
                    <input type="text" name="thumbnail_file_link" class="custom-file-link form-control" id="thumbnail_file_link" value="{{$thumbnail}}">
                    <label class="custom-file-label custom-file-label-thumb ckfinder-popup" id="img"  data-show="img_view" data="thumbnail_file_link"></label>
                </div>
            </div>
            <div class="demo-img" style="padding-top: 15px;">
                @if($thumbnail != "")
                    <img class="img_view" src="{{asset($thumbnail)}}">
                @else
                    <img class="img_view" src="{{asset('assets/images/placeholder.png')}}">
                @endif
            </div>
        </div>
    </div> <!-- /.card-body -->
</div><!-- /.card -->
