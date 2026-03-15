@extends('frontend.layouts.master')

@section('seo')
@endsection

@section('content')
    <main id="contact_complete" class="main">

        {{-- Menu --}}
        @include('frontend.includes.menu')

        {{-- Page Header Start --}}
        <div class="container-fluid page-header pt-5">
            <div class="container text-center">
                <h3 class="animated slideInDown mb-3 fw-bold">@lang('Contact completed')</h3>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">@lang('Home')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('Contact completed')</li>
                    </ol>
                </nav>
            </div>
        </div>
        {{-- Page Header End --}}

        {{-- Contact Completed --}}
        <div class="container-xxl py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-11 col-lg-7">
                        {{-- @if (session('contact_name'))
                            <h3>@lang('Dear') {{ session('contact_name') }}</h3>
                        @endif --}}
                        <div class="attention-info">
                            <p>Cảm ơn bạn đã gửi câu hỏi/ý kiến đóng góp đến cho chúng tôi. Chúng tôi sẽ liên hệ lại với bạn, vì vậy xin vui lòng đợi trong giây lát. Nếu bạn chưa nhận được bất kì phản hồi nào, vui lòng liên hệ lại với chúng tôi qua địa chỉ email dưới đây.</p>
                            <p>
                                MAIL: <a href="mailto:{{ setting_option('email') }}">
                                    {{ setting_option('email') }}
                                </a>
                            </p>
                        </div>
                        <div class="return-btn text-center">
                            <a href="{{ route('index') }}" class="btn btn-success">@lang('Home')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Contact End --}}
    </main>
@endsection
