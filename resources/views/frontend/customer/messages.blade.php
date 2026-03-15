@extends($templatePath . '.layouts.index')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    <section class="py-5 my-post position-relative customer">

        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">My Order</li>
                </ol>
            </div>
            <div class="row">
                <div class="col-lg-3  col-12 mb-4">
                    @include($templatePath . '.customer.includes.sidebar-customer')
                </div>
                <div class="col-lg-9">
                    <div id="talkjs-container" style="height: 650px;"></div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        Talk.ready.then(function() {
            // The core TalkJS lib has loaded, so let's identify the current user  to TalkJS.

            // Note: this code is exactly equal to the `var operator =` declaration in
            // user.html
            var me = new Talk.User({
                // just hardcode any user id, as long as your real users don't have this id
                id: "{{ $user->id }}",
                name: "{{ $user->fullname }}",
                email: "{{ $user->email }}",
                photoUrl: "{{ asset($user->avatar) }}",
                welcomeMessage: "Hi there! How can I help you?"
            });

            // TODO: replace the appId below with the appId provided in the Dashboard
            window.talkSession = new Talk.Session({
                appId: "{{ config('talkjs.app_id') }}",
                me: me
            });

            var inbox = talkSession.createInbox();
            inbox.mount(document.getElementById("talkjs-container"));
        });
    </script>
@endpush
