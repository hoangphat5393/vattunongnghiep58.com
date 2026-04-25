@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = __('admin.news');
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
    <!-- Content Header (Page header) -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">{{ $title_head }}</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $title_head }}</li>
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
                    <div class="mb-4 card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_head }} @lang('admin.list')</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">

                            <div class="d-flex justify-content-between">
                                @include('backend.partials.button_add_delete', ['type' => 'post', 'route' => route('admin.post.create')])
                                <div class="float-end mt-3 mt-lg-0">
                                    <form method="GET" action="" id="frm-filter-post" class="form-inline">
                                        @php
                                            // $categories = App\Models\Backend\Category::select('id', 'name')->where('type', 'post')->orderByDesc('sort')->get();
                                        @endphp
                                        {{-- <select class="custom-select mr-2" name="category_id">
                                            <option value="">@lang('admin.Category')</option>
                                            @foreach ($categories as $item)
                                                <option value="{{ $item->id }}" {{ request('category_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select> --}}
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search_name" id="search_name" placeholder="@lang('admin.keyword')" value="{{ request('search_name') }}">
                                            <button type="submit" class="btn btn-outline-primary" aria-label="@lang('admin.search')">
                                                <i class="fa-regular fa-magnifying-glass"></i> @lang('admin.search')
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between my-4">
                                <div class="float-start">
                                    <b>@lang('admin.Total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> @lang('admin.News')
                                </div>
                                @if (false)
                                    <div class="float-end">
                                        {!! $data->links() !!}
                                    </div>
                                @endif
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
                                            <th class="text-center" style="width:100px">@lang('admin.priority')</th>
                                            <th class="text-center">@lang('admin.name')</th>
                                            {{-- <th class="text-center" style="width:150px">@lang('admin.category')</th> --}}
                                            <th class="text-center">@lang('admin.thumbnail')</th>
                                            <th class="text-center">@lang('admin.Createdby')</th>
                                            <th class="text-center">@lang('admin.Createddate')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="icheck-info d-inline">
                                                        <input type="checkbox" id="{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}">
                                                        <label for="{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" id="sort" class="form-control quick_change_value text-center" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" value="{{ $item->sort }}" reload-on-change>
                                                </td>
                                                <td>
                                                    <a class="row-title fw-bold" href="{{ route('admin.post.edit', $item->id) }}">
                                                        {{ $item->name }}
                                                        {{-- |
                                                        {{ $item->name_en }} --}}
                                                    </a>
                                                    <br>
                                                    <a class="link to-link fw-bold" href="{{ route('news.detail', [$item->slug, $item->id]) }}" target="_blank">
                                                        <span>URL VI: </span>{{ route('news.detail', [$item->slug, $item->id]) }}
                                                    </a>
                                                    {{-- <br>
                                                    <a class="link to-link fw-bold" href="{{ route('news.detail', [$item->slug, $item->id], true, 'en') }}" target="_blank">
                                                        <span>URL EN: </span>{{ route('news.detail', [$item->slug, $item->id], true, 'en') }}
                                                    </a> --}}
                                                </td>
                                                {{-- <td class="text-center">
                                                    @php
                                                        $categories = $item->categories;
                                                    @endphp
                                                    @foreach ($categories as $k => $category)
                                                        <a class="link" target="_blank" href="{{ route('admin.post-category.edit', $category->id) }}">
                                                            {{ $category->name }}
                                                            
                                                        </a></br>
                                                    @endforeach
                                                </td> --}}
                                                <td class="text-center">
                                                    <img src="{{ get_image($item->image) }}" style="height: 70px;">
                                                </td>
                                                <td>
                                                    @if ($item->user)
                                                        <div class="w-fit-content mx-auto">{{ $item->user->name }}</div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->updated_at }}
                                                    <br>
                                                    <input type="checkbox" id="status" class="quick_change_value" @checked($item->status == 1) value="1" value-off="0" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" data-toggle="toggle" data-on="@lang('admin.Publish')" data-off="@lang('admin.Draft')" data-onstyle="success" data-offstyle="light">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if (false)
                                <div class="float-end">
                                    {!! $data->links() !!}
                                </div>
                            @endif
                        </div> <!-- /.card-body -->

                        {{-- card-footer --}}
                        {{ $data->links('backend.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
