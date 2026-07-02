<ul class="d-flex flex-wrap gap-2 mb-4 list-unstyled ps-0">
    <li class="nav-item">
        <a class="btn btn-danger me-2" onclick="delete_id('{{ $type }}')" href="javascript:void(0)"><i class="fas fa-trash"></i> @lang('admin.Delete')</a>
    </li>
    <li class="nav-item">
        <a class="btn btn-primary me-2" href="{{ $route }}"><i class="fas fa-plus"></i> @lang('admin.create_new')</a>
    </li>
</ul>
