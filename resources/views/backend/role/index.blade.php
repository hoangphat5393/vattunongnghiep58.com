@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = __('admin.List permission');
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
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">List Users</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">List Users</li>
                    </ol>
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">List Users</h3>
                        </div> <!-- /.card-header -->

                        <div class="card-body">
                            <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between">
                                @include('backend.partials.button_add', ['type' => 'role', 'route' => route('admin.role.create')])
                            </div>

                            <div class="d-flex justify-content-between align-items-center my-4">
                                {{-- <div class="fl">
                                    <b>@lang('admin.Total')</b>: <span class="bold" style="color: red; font-weight: bold;">{{ $total_item ?? 0 }}</span> @lang('admin.Users')
                                </div>
                                <div class="fr">
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
                                            <th scope="col">@lang('admin.Name')</th>
                                            <th scope="col">@lang('admin.Slug')</th>
                                            <th scope="col">@lang('admin.Permission')</th>
                                            <th scope="col">@lang('admin.Action')</th>
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
                                                    <a href="{{ route('admin.role.edit', $data->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-pen"></i> Edit</a>
                                                    <form action="{{ route('admin.role.destroy', $data->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Remove</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- /.card-body -->
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
