<title>{{ $seo['title'] }}</title>
<meta name="keywords" content="{{ $seo['keywords'] ?? '' }}" />
<meta name="description" content="{{ $seo['description'] ?? '' }}" />

<!-- Facebook Seo -->
<meta property="og:title" content="{{ $seo['og_title'] ?? '' }}" />
<meta property="og:description" content="{{ $seo['og_description'] ?? '' }}" />
<meta property="og:url" content="{{ $seo['og_url'] ?? '' }}" />
<meta property="og:type" content="article" />
<meta property="og:image" content="{{ $seo['og_img'] ?? '' }}" />
<link rel="canonical" href="{{ $seo['current_url'] ?? '' }}" />

<base href="{{ route('index') }}" />
<meta name="robots" content="index,follow,noodp" />
<meta name="author" content="GetAtZ" />
<meta name="copyright" content="Copyright&copy;2020 GetAtZ. All Right Reserved." />
<meta http-equiv="content-script-type" content="text/javascript" />
<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="content-language" content="{{ app()->getLocale() }}" />
<meta name="robots" content="notranslate" />
<meta name="distribution" content="global" />
<meta name="rating" content="general" />
<meta property="og:site_name" content="E-Bike" />

{{-- <link href='//fonts.googleapis.com' rel='dns-prefetch' />
<link href='//ajax.googleapis.com' rel='dns-prefetch' />
<link href='//apis.google.com' rel='dns-prefetch' />
<link href='//connect.facebook.net' rel='dns-prefetch' />
<link href='//www.facebook.com' rel='dns-prefetch' />
<link href='//twitter.com' rel='dns-prefetch' />
<link href='//www.google-analytics.com' rel='dns-prefetch' />
<link href='//www.googletagservices.com' rel='dns-prefetch' />
<link href='//pagead2.googlesyndication.com' rel='dns-prefetch' />
<link href='//googleads.g.doubleclick.net' rel='dns-prefetch' />
<link href='//static.xx.fbcdn.net' rel='dns-prefetch' />
<link href='//platform.twitter.com' rel='dns-prefetch' />
<link href='//syndication.twitter.com' rel='dns-prefetch' /> --}}

<link href="mailto:{{ setting_option('email') }}" rev="made" />
<link href="{{ asset('/') }}" rel="index" />
