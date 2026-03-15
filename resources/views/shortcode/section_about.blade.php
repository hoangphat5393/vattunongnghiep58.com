@php
    extract($data);

    $textField = \App\Models\Field::where('section', $data['section'])
        ->where('type', 'text')
        ->pluck('content', 'handleId')
        ->all();

    $imageField = \App\Models\Field::where('section', $data['section'])
        ->where('type', 'image')
        ->pluck('image', 'handleId')
        ->all();
@endphp


<div class="container-fluid about-block py-5">
    <div class="ellipse-about"></div>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="section-title">
                    {!! htmlspecialchars_decode($textField['title1']) !!}
                    {!! htmlspecialchars_decode($textField['title2']) !!}
                    {!! htmlspecialchars_decode($textField['title3']) !!}
                </div>
                <div class="animation-line">
                    <div class="line"></div>
                </div>
                <div class="content-about">
                    {!! htmlspecialchars_decode($textField['description']) !!}
                </div>
                <div class="content-about">
                    <div class="list-about">
                        <div class="item-about">
                            {!! htmlspecialchars_decode($textField['field1']) !!}

                        </div>
                        <div class="item-about">
                            {!! htmlspecialchars_decode($textField['field2']) !!}
                        </div>
                        <div class="item-about">
                            {!! htmlspecialchars_decode($textField['field3']) !!}
                        </div>
                        <div class="item-about">
                            {!! htmlspecialchars_decode($textField['field4']) !!}
                        </div>
                    </div>
                    <a href="{{ route('page', 'about') }}">Tìm hiểu thêm</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-right">
                    <div class="about-top"></div>
                    <div class="about-img">
                        <img src="{{ $imageField['image1'] }}" />
                    </div>
                    <div class="founded-year">
                        {!! htmlspecialchars_decode($textField['field5']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
