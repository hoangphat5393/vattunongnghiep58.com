<link href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" rel="stylesheet">
<link href="{{ asset('vendor/harimayco-menu/plugins/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ asset('vendor/harimayco-menu/plugins/js/fontawesome-iconpicker.js') }}"></script>

<div class="card">
    <div class="card-header">
        <h5>{{ $title ?? 'Icon' }}</h5>
    </div> <!-- /.card-header -->
    <div class="card-body">
        <div class="form-group">
            <label>As a component</label>

            <div class="input-group">
                <input data-placement="bottomRight" name="icon" class="form-control icp icp-auto" value="{{ $icon ?? '' }}" type="text" autocomplete="off" />
                <span class="input-group-text iconpicker-component"></span>
            </div>
        </div>
    </div> <!-- /.card-body -->
</div><!-- /.card -->

<script type="text/javascript">
    $(function() {
        $('.icp-auto').iconpicker();
        $('.icp').on('iconpickerSelected', function(e) {
            $(this).parent().find('input').val(e.iconpickerValue);
            $(this).parent().find('.dropdown-menu').removeClass('show');
        });
    });
</script>
