
@php
 $settings = SiteSetting();     
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>{{$settings['website_title']}}</title>
    <link rel="icon" type="image/png" href="@isset($settings['favicon']){{asset('storage/dashboard/images/logo/'.$settings['favicon'])}}@else{{asset('frontend/images/favicon.png')}}@endif">
    <!-- fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i">
    <!-- css -->
    <link rel="stylesheet" href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css')}}">
    <!-- font - fontawesome -->
    <link rel="stylesheet" href="{{ asset('frontend/vendor/fontawesome/css/all.min.css')}}">
    <!-- font - stroyka -->
    <link rel="stylesheet" href="{{ asset('frontend/fonts/stroyka/stroyka.css')}}">

    @isset($settings['theme_skin'])
      @if($settings['theme_skin']!='custom')
        <link rel="stylesheet" href="{{ asset('frontend/css_'.$settings['theme_skin'].'/style.css')}}">
      @else
        <link rel="stylesheet" href="{{ asset('frontend/css/style.css')}}">
      @endif
    @endisset


</head>
<body>
    <style>img{
       max-height: 100px;
    }</style>

<div style="margin-top:200px; text-align:center">
<h5>we're just tracking your visit so you can earn cashback with our partner.</h5>
    <h2 class="mb-5 color-primary">Simply complete your purchase and we'll do the rest.</h2>
    <img class="mr-5"  src="@if(isset($settings['website_logo']) && $settings['website_logo']!='default.png'){{asset('storage/dashboard/images/logo/'.$settings['website_logo'])}}@else{{asset('admin-dashboard/images/logo.png')}}@endif" alt="logo">
    <img class="" src="{{asset('frontend/images/pages/redirecting.gif')}}" alt="logo">

    <img class="ml-5"
    @if($store->logo->first())
    @if($store->logo->first()->is_fake)
        src="{{asset('frontend/images/logos/'.$store->logo->first()->image)}}"
    @else
        src="{{asset('storage/stores/images/'.$store->logo->first()->image)}}"
    @endif
@else
    src="{{asset('frontend/images/products/product-16.jpg')}}" 
@endif alt="">
   <p class="mt-3"> If you are not redirected after 3 seconds please continue to <a href="{{$url}}">{{$store->name}}</a></p>
    
</div>
<script src="http://code.jquery.com/jquery-1.9.1.js" type="text/javascript"></script>
<script type="text/javascript">
    
    var delay = 3000; 
    var url = '{{$url}}'
    setTimeout(function(){ window.location = url; }, delay);

    
</script>
</body>