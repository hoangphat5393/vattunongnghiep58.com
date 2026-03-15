@php extract($data) @endphp
@php
@endphp

<section class="block10">
    <div class="mainBanner">
        <div class="container main-menu">
            @include('theme.includes.menu')
        </div>
        <div class="container-fluid px-0">
            <div class="row g-0">
                <div class="col-lg-12 banner-left">
                    <img class="img-fluid object-fit-cover w-100" src="{{ get_image($page->image) }}" alt="{{ $page->name }}">
                </div>
            </div>
        </div>
    </div>
</section>
