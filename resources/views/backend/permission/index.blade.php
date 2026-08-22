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
                                @include('backend.partials.button_add', ['type' => 'permission', 'route' => route('admin.permission.create')])
                            </div>

                            <div class="my-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <b>@lang('admin.total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> {{ $title_head }}
                                </div>
                                {{-- <div class="float-start">
                                    <b>@lang('admin.Total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> @lang('admin.Permissions')
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
                                            <th scope="col">@lang('admin.http.path')</th>
                                            <th scope="col">@lang('admin.action')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($permissions as $data)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="icheck-info d-inline">
                                                        <input type="checkbox" id="{{ $data->id }}" name="seq_list[]" value="{{ $data->id }}">
                                                        <label for="{{ $data->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.permission.edit', $data->id) }}" title="">{{ $data->name }}</a>
                                                </td>
                                                <td>{{ $data->slug }}</td>
                                                <td>
                                                    @php
                                                        $httpUriHtml = '';
                                                        if ($data->http_uri) {
                                                            $methods = array_map(function ($value) {
                                                                $route = explode('::', $value);
                                                                $methodStyle = '';
                                                                if ($route[0] == 'ANY') {
                                                                    $methodStyle = '<span class="badge text-bg-info">' . $route[0] . '</span>';
                                                                } elseif ($route[0] == 'POST') {
                                                                    $methodStyle = '<span class="badge text-bg-warning">' . $route[0] . '</span>';
                                                                } else {
                                                                    $methodStyle = '<span class="badge text-bg-primary">' . $route[0] . '</span>';
                                                                }
                                                                return $methodStyle . ' <code>' . $route[1] . '</code>';
                                                            }, explode(',', $data->http_uri));
                                                            $httpUriHtml = implode('<br>', $methods);
                                                        }
                                                    @endphp
                                                    {!! $httpUriHtml !!}
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.permission.edit', $data->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-pen"></i> @lang('admin.edit')</a>
                                                    <form action="{{ route('admin.permission.destroy', $data->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
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

                        {{ $permissions->links('backend.pagination.custom') }}
                    </div><!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div>
    @endsection

    @push('scripts')
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
    @endpush
