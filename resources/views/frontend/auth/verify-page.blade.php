@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    <section id="user-profile" class="py-5 my-post position-relative customer">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4">
                    <div class="breadcrumbs mb-3">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active">Verify</li>
                        </ol>
                    </div>

                    @foreach ($errors->all() as $error)
                        <div class="error">{{ $error }}</div>
                    @endforeach

                    <form accept-charset="UTF-8" method="post" action="">
                        @csrf
                        <div class="mb-3">
                            <h4>Enter your code:</h4>
                            <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" placeholder="Enter your code">
                        </div>
                        <div class="mb-3 text-center">
                            <button name="button" type="submit" class="btn btn-custom">Verify</button>
                        </div>
                    </form>
                    <hr class="my-5" />
                    <h4>Didn't you receive the code?</h4>
                    <form accept-charset="UTF-8" method="post" action="{{ route('resend') }}">
                        @if (Session::has('message'))
                            <div class="text-success">{{ session()->get('message') }}</div>
                        @endif

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="channel_sms" name="channel" value="sms" checked>
                                <label class="form-check-label" for="channel_sms">SMS</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="channel_call" value="call" name="channel">
                                <label class="form-check-label" for="channel_call">Call</label>
                            </div>
                        </div>
                        <div class="mb-3 text-center">
                            @if (Session::has('error'))
                                <div class="text-danger mb-3">{{ session()->get('error') }}</div>
                            @endif
                            <button name="form-submit" type="submit" class="btn btn-custom">Resend</button>
                        </div>
                    </form>
            </div>
        </div>
        </div>
    </section>
@endsection

@push('scripts')
@endpush
