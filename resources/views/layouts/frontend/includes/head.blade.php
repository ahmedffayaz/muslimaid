<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>Cashback Reborn</title>
    <link rel="icon" type="image/png" href="{{asset('frontend/images/favicon.png')}}">
    <!-- fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i">
    <!-- css -->
    <link rel="stylesheet" href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('frontend/vendor/owl-carousel/assets/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ asset('frontend/vendor/photoswipe/photoswipe.css')}}">
    <link rel="stylesheet" href="{{ asset('frontend/vendor/photoswipe/default-skin/default-skin.css')}}">
    <link rel="stylesheet" href="{{ asset('frontend/vendor/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css')}}">
    <!-- font - fontawesome -->
    <link rel="stylesheet" href="{{ asset('frontend/vendor/fontawesome/css/all.min.css')}}">
    <!-- font - stroyka -->
    <link rel="stylesheet" href="{{ asset('frontend/fonts/stroyka/stroyka.css')}}">

@php
 $settings = Config::get('settings');   

@endphp
@if($settings['ga_tracking_id'])
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{$settings['ga_tracking_id']}}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', "{{$settings['ga_tracking_id']}}");
</script>
@endif
</head>