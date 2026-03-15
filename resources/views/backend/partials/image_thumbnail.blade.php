<div class="card">
    <div class="card-header">
        <h3 class="card-title">Image Thumbnail</h3>
    </div> <!-- /.card-header -->
    <div class="card-body">
        <div class="form-group">
            <label for="post_title">Thumbnail Alt</label>
            <input type="text" class="form-control" id="post_thumb_alt" value="{{$thumbnail_alt}}" name="post_thumb_alt" placeholder="Thumbnail Alt">
        </div>
        <div class="form-group">
            <label for="thumbnail_file">File input</label>
            <div class="input-group">
                <div class="custom-file">
                    <input type="text" name="thumbnail_file_link" class="custom-file-link form-control" id="thumbnail_file_link" value="{{$thumbnail}}">
                    <label class="custom-file-label custom-file-label-thumb btn-images" data-show="view-img" data="thumbnail_file_link"></label>
                </div>
            </div>
            <div class="demo-img" style="padding-top: 15px;">
                @if($thumbnail != "")
                    <img class="thumbnail_file_link" src="{{asset('storage/'.$thumbnail)}}">
                @else
                    <img class="thumbnail_file_link" src="{{asset('assets/images/placeholder.png')}}">
                @endif
            </div>
        </div>
    </div> <!-- /.card-body -->
</div><!-- /.card -->
