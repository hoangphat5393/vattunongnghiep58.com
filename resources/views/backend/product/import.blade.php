@extends('backend.layouts.master')
@section('seo')
    <?php
    $data_seo = [
        'title' => 'Xử lý data | ' . setting_option('seo-title-add'),
        'keywords' => setting_option('seo-keywords-add'),
        'description' => setting_option('seo-description-add'),
        'og_title' => 'Xử lý data | ' . setting_option('seo-title-add'),
        'og_description' => setting_option('seo-description-add'),
        'og_url' => Request::url(),
        'og_img' => asset('assets/images/logo_seo.png'),
        'current_url' => Request::url(),
        'current_url_amp' => '',
    ];
    $seo = WebService::getSEO($data_seo);
    ?>
    @include('backend.partials.seo')
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Xử lý data</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end"><ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
                        <li class="breadcrumb-item active">Xử lý data</li>
                    </ol></nav>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.app-content-header -->
    <!-- Main content -->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Xử lý data</h4>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <div class="mt-2">
                                <form action="{{ route('admin.product.import_process') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @isset($success)
                                        <div class="mgt-10  alert alert-success alert-dismissible" role="alert">
                                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            {!! $success !!}
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="file_input">File Excel (Chọn file Excel nếu bạn đang update Đồ Điện)</label>
                                            <input type="file" name="file_input" id="file_input" class="form-control-file">
                                        </div>
                                        <div class="form-group text-center">
                                            <button type="submit" class="btn btn-success">Import</button>
                                        </div>
                                    </form>
                                </div>
                                <br />

                            </div> <!-- /.card-body -->
                        </div><!-- /.card -->
                    </div> <!-- /.col -->
                </div> <!-- /.row -->
            </div> <!-- /.container-fluid -->
        </div>
    @endsection
