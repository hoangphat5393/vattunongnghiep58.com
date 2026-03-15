<!DOCTYPE html>
<html>
<head>
    <title>{{ $detail['subject'] }}</title>
</head>

<body>
    @if($detail['url_current'] != '')
        <h1>Yêu cầu liên hệ lại từ : {!! setting_option('name-company') !!}</h1>
        @isset($product_title)
            <p>Bất động sản yêu cầu: <a href="{{ url($detail['url_current']) }}">{{ $product_title ?? '' }}</a></p>
        @endisset
    @else
    <h1>{{ $detail['subject'] }}</h1>
    @endif
    <p>{{ $detail['message'] }}</p> 
    <br>
    <hr>
    <p>From: {{ $detail['name'] }}</p>
    <p>Email: {{ $detail['email'] }}</p>
    @if (isset($detail['phone']) && !empty($detail['phone']))
    <p>Phone: {{ $detail['phone'] }}</p>
    @endif
</body>
</html>