<div class="card">
    <div class="card-header">Dimensions & Weight</div> <!-- /.card-header -->
    <div class="card-body">
            <h4>Dimensions: (inch)</h4>
        <div class="row">
            <div class="form-group col-lg-4">
                <label for="height" class="title_txt">Height</label>
                <input type="text" name="height" id="height" value="{{ $height ?? '' }}" class="form-control">
            </div>
            <div class="form-group col-lg-4">
                <label for="width" class="title_txt">Width</label>
                <input type="text" name="width" id="width" value="{{ $width ?? '' }}" class="form-control">
            </div>
            <div class="form-group col-lg-4">
                <label for="length" class="title_txt">Length</label>
                <input type="text" name="length" id="length" value="{{ $length ?? '' }}" class="form-control">
            </div>
        </div>
            <h4>Weight: (Pounds)</h4>
        <div class="row">
            <div class="form-group col-lg-4">
                <label for="weight" class="title_txt">Weight</label>
                <input type="text" name="weight" id="weight" value="{{ $weight ?? '' }}" class="form-control">
            </div>
        </div>
    </div>
</div>
