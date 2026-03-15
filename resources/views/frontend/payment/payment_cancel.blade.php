@extends($templatePath .'.layouts.index')

@section('seo')
@include($templatePath .'.layouts.seo', $seo??[] )
@endsection

@section('content')
    <div id='page-content'>
        <div class="page-wrapper container my-5">
            <div class="row">
                <div class="col-12 mx-auto">
                    {!! htmlspecialchars_decode($content_success->content) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
