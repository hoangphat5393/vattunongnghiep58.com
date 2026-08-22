@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = 'Cấu hình Menu';
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

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/menu-builder.css') }}">
@endpush

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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title_head }}</li>
                    </ol></nav>
                </div>
            </div>
        </div>
    </div>
    {{-- end::App Content Header --}}

    {{-- begin::App Content --}}
    <div class="app-content">
        <div class="container-fluid">
            {{-- begin::Row --}}
            <div class="row">
                <div class="col-md-12">
                    {{-- card --}}
                    <div class="card card-primary card-outline mb-4">

                        {{-- card-header --}}
                        <div class="card-header">
                            <h3 class="card-title mb-0">
                                <i class="fa-solid fa-bars me-2" aria-hidden="true"></i>{{ $title_head }}
                            </h3>
                        </div>

                        {{-- card-body --}}
                        <div class="card-body">
                            @include('backend.setting.menu-html')
                        </div>
                    </div>
                    {{-- end::card --}}
                </div>
            </div>
            {{-- end::Row --}}
        </div>
    </div>
    {{-- end::App Content --}}
@endsection


@push('scripts')
    <script>
        var menus = {
            "oneThemeLocationNoMenus": "",
            "moveUp": "Di chuyển lên",
            "moveDown": "Di chuyển xuống",
            "moveToTop": "Chuyển lên đầu",
            "moveUnder": "Chuyển thành mục con của %s",
            "moveOutFrom": "Chuyển ra ngoài %s",
            "under": "Dưới %s",
            "outFrom": "Thoát khỏi %s",
            "menuFocus": "%1$s. Mục menu %2$d trên %3$d.",
            "subMenuFocus": "%1$s. Menu con %2$d trên %3$s."
        };

        var arraydata = [];
        var menuwr = "{{ url()->current() }}";
    </script>

    <script type="text/javascript" src="{{ asset('assets/laravel-menu/scripts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/laravel-menu/scripts2.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/laravel-menu/menu.js') }}"></script>

    <script type="text/javascript">
        document.addEventListener('input', function(event) {
            if (!event.target.matches('.menu-source-filter')) {
                return;
            }

            const query = event.target.value.trim().toLowerCase();
            const container = event.target.closest('.inside');
            if (!container) {
                return;
            }

            container.querySelectorAll('.menu-source-item').forEach(function(item) {
                const label = item.querySelector('.menu-source-title');
                const text = label ? label.textContent.trim().toLowerCase() : item.textContent.trim().toLowerCase();
                item.hidden = query !== '' && !text.includes(query);
            });
        });
    </script>
@endpush
