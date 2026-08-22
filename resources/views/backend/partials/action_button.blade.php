@php

    $status = $status ?? 1;

    $date_create = isset($created_at) ? $created_at : date('Y-m-d H:i:s');

    $date_update = isset($updated_at) ? $updated_at : date('Y-m-d H:i:s');

@endphp



<div class="mb-4 card card-success card-outline">

    <div class="card-header">

        <h5 class="card-title mb-0">@lang('admin.Publish')</h5>

    </div> <!-- /.card-header -->

    <div class="card-body">

        <div class="mb-3 form-group">

            <label for="created_at" class="form-label">@lang('admin.Createddate'):</label>

            <div class="input-group">

                <input type="text" id="created_at" name="created_at" class="form-control" value="{{ $date_create }}">

                <div class="input-group-append input-append-date">

                    <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>

                </div>

            </div>

        </div>



        <div class="mb-3 form-group">

            <label for="updated_at" class="form-label">@lang('admin.Updateddate'):</label>

            <div class="input-group">

                <input type="text" id="updated_at" name="updated_at" class="form-control" value="{{ $date_update }}">

                <div class="input-group-append input-append-date">

                    <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>

                </div>

            </div>

        </div>



        <div class="mb-3 d-flex justify-content-end">

            <div class="icheck-primary d-inline me-3">

                <input type="radio" id="radioDraft" name="status" value="0" {{ $status == 0 ? 'checked' : '' }}>

                <label for="radioDraft" class="form-check-label">@lang('admin.Draft')</label>

            </div>

            <div class="icheck-primary d-inline">

                <input type="radio" id="radioPublic" name="status" value="1" {{ $status == 1 ? 'checked' : '' }}>

                <label for="radioPublic" class="form-check-label">@lang('admin.Publish')</label>

            </div>

        </div>

        <div class="form-group text-end">

            <button type="submit" name="submit" value="save" class="btn btn-info">@lang('admin.Save')</button>

            <button type="submit" name="submit" value="apply" class="btn btn-success">@lang('admin.Save_Edit')</button>

        </div>

    </div> <!-- /.card-body -->

</div><!-- /.card -->



@push('scripts')
    <script>
        //Date range picker

        $('#created_at').datetimepicker({

            format: 'Y-m-d H:m:s',

            timepicker: false,

            // showTimezone: true,

        });



        $('#updated_at').datetimepicker({

            format: 'Y-m-d H:m:s',

            timepicker: false,

            // showTimezone: true,
        });



        $('.input-append-date').on('click', function() {

            $(this).siblings('input').datetimepicker('show'); //support hide,show and destroy command

        });
    </script>
@endpush
