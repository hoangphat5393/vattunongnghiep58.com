@extends('frontend.layouts.master')

@section('content')
    {{-- error --}}
    <section class="space-ptb bg-holder my-5">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6">
                    <div class="error-404 text-center">
                        <h1>500</h1>
                        <strong>Lỗi server</strong>
                        <span>Quay về <a href="{{ route('index') }}"> Trang chủ </a></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- error --}}
@endsection
