<div class="row mb-3">
    <div class="col-md-6">
        <label for="name" class="form-label">@lang('admin.domain')</label>
        <input type="text" class="form-control title_slugify" id="name" name="name" placeholder="@lang('admin.domain')" value="{{ $name ?? '' }}">
    </div>
    <div class="col-md-6">
        <label for="supplier" class="form-label">@lang('admin.supplier')</label>
        <input type="text" class="form-control" id="supplier" name="supplier" placeholder="@lang('admin.supplier')" value="{{ $supplier ?? '' }}">
    </div>
</div>
@php
    $hostings = App\Models\Backend\Hosting::select('id', 'name', 'ip_address', 'supplier')->where('status', 1)->orderByDesc('sort')->get();
@endphp
<div class="mb-3">
    <label for="hosting_id" class="form-label">@lang('admin.hosting')</label>
    <select class="form-control" id="hosting_id" name="hosting_id">
        <option value=0>Chọn hosting</option>
        @if ($hostings)
            @foreach ($hostings as $hosting)
                <option value="{{ $hosting->id }}" {{ isset($hosting_id) && $hosting_id == $hosting->id ? 'selected' : '' }}>
                    IP: {{ $hosting->ip_address }} | {{ $hosting->name }} | Supplier: {{ $hosting->supplier }}
                </option>
            @endforeach
        @endif
    </select>
</div>

<div class="mb-3">
    <label for="login_link" class="form-label">@lang('admin.login link')</label>
    <input type="text" class="form-control" id="login_link" name="login_link" placeholder="@lang('admin.login link')" value="{{ $login_link ?? '' }}">
    @if ($id > 0)
        <p class="my-2">
            <strong class="text-primary">Link:</strong>
            <u><i><a href="{{ $login_link ?? '' }}" target="_blank" class="text-red">{{ $login_link ?? '' }}</a></i></u><br>
        </p>
    @endif
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label for="username" class="form-label">@lang('admin.username')</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="@lang('admin.username')" value="{{ $username ?? '' }}">
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label">@lang('admin.password')</label>
        <input type="text" class="form-control" id="password" name="password" placeholder="@lang('admin.password')" value="{{ $password ?? '' }}">
    </div>

    <div class="col-md-6">
        <label for="ftp_username" class="form-label">FTP @lang('admin.username')</label>
        <input type="text" class="form-control" id="ftp_username" name="ftp_username" placeholder="FTP @lang('admin.username')" value="{{ $ftp_username ?? '' }}">
    </div>

    <div class="col-md-6">
        <label for="ftp_password" class="form-label">FTP @lang('admin.password')</label>
        <input type="text" class="form-control" id="ftp_password" name="ftp_password" placeholder="FTP @lang('admin.password')" value="{{ $ftp_password ?? '' }}">
    </div>
</div>

<div class="mb-3">
    <label for="db_name" class="form-label">DB @lang('admin.name')</label>
    <input type="text" class="form-control" id="db_name" name="db_name" placeholder="DB @lang('admin.name')" value="{{ $db_name ?? '' }}">
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label for="db_username" class="form-label">DB @lang('admin.username')</label>
        <input type="text" class="form-control" id="db_username" name="db_username" placeholder="DB @lang('admin.username')" value="{{ $db_username ?? '' }}">
    </div>
    <div class="col-md-6">
        <label for="db_password" class="form-label">DB @lang('admin.password')</label>
        <input type="text" class="form-control" id="db_password" name="db_password" placeholder="DB @lang('admin.password')" value="{{ $db_password ?? '' }}">
    </div>
</div>
