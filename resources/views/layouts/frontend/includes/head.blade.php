<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>{{$settings['website_title']}}</title>
    <link rel="icon" type="image/png" href="@if(isset($settings['favicon']) && $settings['favicon']!='default.png'){{asset('storage/dashboard/images/logo/'.$settings['favicon'])}}@else{{asset('admin-dashboard/images/favicon.png')}}@endif">
    <!-- fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i">
    <!-- css -->
    <link rel="stylesheet" href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('frontend/vendor/owl-carousel/assets/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ asset('frontend/vendor/photoswipe/photoswipe.css')}}">
    <link rel="stylesheet" href="{{ asset('frontend/vendor/photoswipe/default-skin/default-skin.css')}}">
    <link rel="stylesheet" href="{{ asset('frontend/vendor/select2/css/select2.min.css')}}">
    @isset($settings['theme_skin'])
      @if($settings['theme_skin']!='custom')
        <link rel="stylesheet" href="{{ asset('frontend/css_'.$settings['theme_skin'].'/style.css')}}">
      @else
        <link rel="stylesheet" href="{{ asset('frontend/css/style.css')}}">
      @endif
    @endisset
    <!-- font - fontawesome -->
    <link rel="stylesheet" href="{{ asset('frontend/vendor/fontawesome/css/all.min.css')}}">
    <!-- font - stroyka -->
    <link rel="stylesheet" href="{{ asset('frontend/fonts/stroyka/stroyka.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laraberg/css/laraberg.css')}}">
    <script src="https://unpkg.com/react@16.8.6/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@16.8.6/umd/react-dom.production.min.js"></script>
    <script src="{{ asset('vendor/laraberg/js/laraberg.js') }}"></script>
    @livewireStyles

@if(isset($settings['ga_tracking_id']))
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