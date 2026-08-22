@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = __('admin.users');
        $seo = [
            'title' => $title_head,
            'keywords' => '',
            'description' => '',
            'og_title' => $title_head,
            'og_description' => '',
            'og_url' => Request::url(),
            'og_img' => asset('assets/images/logo_seo.png'),
            'current_url' => Request::url(),
            'current_url_amp' => '',
        ];
    @endphp
    @include('backend.partials.seo')
@endsection


@section('content')
    {{-- begin::App Content Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">{{ $title_head }}</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end"><ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title_head }}</li>
                    </ol></nav>
                </div>
            </div>
        </div>
    </div>
    {{-- end::App Content Header --}}

    <!-- Main content -->
    <div class="app-content">
        <div class="container-fluid">

            {{-- begin::Row --}}
            <div class="row">
                <div class="col-md-12">

                    {{-- card --}}
                    <div class="card card-primary card-outline mb-4">

                        {{-- card-header --}}
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_head }}</h3>
                        </div>

                        <div class="card-body">
                            <div class="d-flex flex-column flex-lg-row justify-content-between">
                                @include('backend.partials.button_add_delete', ['type' => 'user', 'route' => route('admin.user.create')])
                                <div>
                                    <form method="GET" action="" id="frm-filter-post" class="form-inline">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="@lang('admin.name')" aria-label="@lang('admin.Keyword')" aria-describedby="name" value="{{ request('name') }}">
                                            <button class="btn btn-outline-primary" type="submit" id="button-addon2" aria-label="@lang('admin.search')">
                                                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between my-4">
                                <div>
                                    <b>@lang('admin.total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> @lang('admin.users')
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover list-data v-center" id="table_index">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:50px">
                                                <div class="icheck-info d-inline">
                                                    <input type="checkbox" id="selectall" onclick="select_all()">
                                                    <label for="selectall"></label>
                                                </div>
                                            </th>
                                            <th scope="col">@lang('admin.name')</th>
                                            <th scope="col">@lang('admin.email')/@lang('admin.username')</th>
                                            <th scope="col">@lang('admin.roles')</th>
                                            <th scope="col">@lang('admin.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="icheck-info d-inline">
                                                        <input type="checkbox" id="{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}">
                                                        <label for="{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.user.edit', $item->id) }}">{{ $item->name }}</a>
                                                </td>
                                                <td>
                                                    {{ $item->email }}
                                                </td>
                                                <td>
                                                    @if ($item->roles->count())
                                                        @foreach ($item->roles as $role)
                                                            <span class="badge text-bg-success">{{ $role->name }}</span>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.user.edit', $item->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-pen"></i> @lang('admin.edit')</a><a href="" title=""></a>
                                                    {{-- @if ($item->id != auth()->user()->id)
                                                        <a href="{{ route('admin.userAdmin.destroy', $item->id) }}" class="btn btn-danger btn-sm btn_deletes"><i class="fa fa-trash"></i> @lang('admin.delete')</a><a href="" title=""></a>
                                                    @endif --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- card-footer --}}
                        {{ $users->links('backend.pagination.custom') }}
                        {{-- end::card --}}
                    </div>
                </div>
            </div>
            {{-- end::Row --}}
        </div>
    </div>
    {{-- end::App Content --}}
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function($) {
            $('#btn_deletes').click(function() {
                if (confirm('Bạn có chắc muốn xóa tài khoản?')) {
                    return true;
                }
                return false;
            });
        });
    </script>
@endpush
