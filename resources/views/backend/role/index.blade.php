@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = __('admin.roles');
        $seo = [
            'title' => $title_head . ' | ' . setting_option('seo-title-add'),
            'keywords' => setting_option('seo-keywords-add'),
            'description' => setting_option('seo-description-add'),
            'og_title' => 'List Category Product | ' . setting_option('seo-title-add'),
            'og_description' => setting_option('seo-description-add'),
            'og_url' => Request::url(),
            'og_img' => asset('assets/images/logo_seo.png'),
            'current_url' => Request::url(),
            'current_url_amp' => '',
        ];
    @endphp
    @include('backend.partials.seo')
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">{{ $title_head }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end"><ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
                        <li class="breadcrumb-item active">{{ $title_head }}</li>
                    </ol></nav>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="mb-4 card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_head }}</h3>
                        </div> <!-- /.card-header -->

                        <div class="card-body">
                            <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between">
                                @include('backend.partials.button_add', ['type' => 'role', 'route' => route('admin.role.create')])
                            </div>

                            <div class="my-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <b>@lang('admin.total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> {{ $title_head }}
                                </div>
                                {{-- <div class="float-start">
                                    <b>@lang('admin.Total')</b>: <span class="bold" style="color: red; font-weight: bold;">{{ $total_item ?? 0 }}</span> @lang('admin.Users')
                                </div>
                                <div class="float-end">
                                    {!! $permissions->links() !!}
                                </div> --}}
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered list-data v-center" id="table_index">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:50px">
                                                <div class="icheck-info d-inline">
                                                    <input type="checkbox" id="selectall" onclick="select_all()">
                                                    <label for="selectall"></label>
                                                </div>
                                            </th>
                                            <th scope="col">@lang('admin.name')</th>
                                            <th scope="col">@lang('admin.slug')</th>
                                            <th scope="col">@lang('admin.permission')</th>
                                            <th scope="col">@lang('admin.action')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($roles as $data)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="icheck-info d-inline">
                                                        <input type="checkbox" id="{{ $data->id }}" name="seq_list[]" value="{{ $data->id }}">
                                                        <label for="{{ $data->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.role.edit', $data->id) }}" title="">{{ $data->name }} | {{ $data->name_en }}</a>
                                                </td>
                                                <td>{{ $data->slug }}</td>
                                                <td>
                                                    @php
                                                        $showPermission = '';
                                                        if ($data->permissions->count()) {
                                                            foreach ($data->permissions as $key => $p) {
                                                                $showPermission .= '<span class="badge text-bg-success">' . $p->name . '</span> ';
                                                            }
                                                        }
                                                    @endphp
                                                    {!! $showPermission !!}
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.role.edit', $data->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-pen"></i> @lang('admin.edit')</a>
                                                    <form action="{{ route('admin.role.destroy', $data->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> @lang('admin.delete')</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- /.card-body -->

                        {{ $roles->links('backend.pagination.custom') }}
                    </div><!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.btn_deletes').click(function() {
                if (confirm('Bạn có chắc muốn xóa tài khoản?')) {
                    return true;
                }
                return false;
            });

        });
    </script>
@endsection
