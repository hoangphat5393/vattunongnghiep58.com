@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = __('admin.news category');
        $seo = [
            'title' => $title_head . ' | ' . setting_option('seo-title-add'),
            'keywords' => setting_option('seo-keywords-add'),
            'description' => setting_option('seo-description-add'),
            'og_title' => $title_head,
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
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">{{ $title_head }}</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
                            <li class="breadcrumb-item active">{{ $title_head }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="mb-4 card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_head }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-column flex-lg-row justify-content-between">
                                @include('backend.partials.button_add_delete', ['type' => 'post-category', 'route' => route('admin.post-category.create')])
                            </div>

                            <div class="my-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <b>@lang('admin.total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> @lang('admin.news category')
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover list-data v-center" id="table_index">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center" style="width:50px">
                                                <div class="icheck-info d-inline">
                                                    <input type="checkbox" id="selectall" onclick="select_all()">
                                                    <label for="selectall"></label>
                                                </div>
                                            </th>
                                            <th scope="col" class="text-center" style="width:100px">@lang('admin.sort')</th>
                                            <th scope="col" class="text-center">@lang('admin.name')</th>
                                            <th scope="col" class="text-center">@lang('admin.thumbnail')</th>
                                            <th scope="col" class="text-center">@lang('admin.created date')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($categories->count())
                                            @include('backend.post-category.includes.category_item', [
                                                'level' => 0,
                                                'categories' => $categories,
                                                'childrenMap' => $childrenMap ?? collect(),
                                            ])
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $categories->links('backend.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
