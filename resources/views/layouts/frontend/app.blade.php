@php
     $settings = SiteSetting();   
    //  dd($settings);
    @endphp
<!DOCTYPE html>
<html lang="en" dir="ltr">

    @include('layouts.frontend.includes.head')

<body>
    <style>.account-menu__links {
        
        padding: 6px 0;
        
    }
    
    @media(min-width: 768px){
        .slider-store-name{
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 18px;
        }
    }
    @media(max-width: 767px){
        .slider-store-name {
        text-align: center;
        position: relative;
        font-size: 18px !important;
        }
        .slider_pic{
            margin:auto;
        }
    }
    .suggestions__item.selected{background-color:#faf2e2}
    
    #accordion .btn.focus, .btn:focus {
    box-shadow: none;
    }
    [dir=ltr] .nav-links__item--has-submenu .nav-links__item-body {
        padding-right: 20px;
        padding-left: 20px;
    }
    .site-header__logo {
        -ms-flex-negative: 0;
        flex-shrink: 0;
        width: 150px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: left;
        -ms-flex-align: left;
        align-items: left;
        -webkit-box-pack: left;
        -ms-flex-pack: left;
        justify-content: left;
        color: inherit;
    }
    .departments__body{
        z-index: 1;
    }
    .nav-panel{
        background: rgba(299, 299, 299, 0.97);
        color: black

    }
    .site-header__middle {
        height: 70px;
        padding-top: 25px;
        padding-bottom: 20px;
    }
    .nav-links__item--hover .nav-links__item-body {
        background: #EDEEEE;
    }
   .block-finder__body {
        background: none no-repeat;
    }
    .block-finder__title{
        background-color: #fff;
        padding: 0.5rem 1rem;
        position: absolute;
        left: 3rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.5rem;
    }
    </style>
    <!-- site -->
    @isset($settings['theme_skin'])
        @if($settings['theme_skin']=='custom')
            @include('layouts.frontend.includes.colors')
        @endif
    @endisset
    
    @if(Session::has('login-welcome'))
    <div class="toast bg-success m-2" role="alert" aria-live="assertive" aria-atomic="true"style="position:absolute; top:0; right:0; z-index: 200">
        <div class="toast-header p-3">
            <strong class="mr-auto">Welcome back {{Auth::user()->first_name}} {{Auth::user()->last_name}}.</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    @endif

    <div class="site">
        <!-- mobile site__header -->
        @include('layouts.frontend.includes.mobile_header')

        <!-- mobile site__header / end -->
        <!-- desktop site__header -->
        @include('layouts.frontend.includes.header')

        <!-- desktop site__header / end -->
        <!-- site__body -->
        <div class="site__body">
            @yield('content')
        </div>
        <!-- site__body / end -->
        <!-- site__footer -->
        @include('layouts.frontend.includes.footer')

        <!-- site__footer / end -->
    </div>
    <!-- site / end -->
    <!-- quickview-modal -->
    <div id="quickview-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content"></div>
        </div>
    </div>
    <!-- quickview-modal / end -->
    <!-- mobilemenu -->
    @include('layouts.frontend.includes.mobile_menu')
   
    <!-- mobilemenu / end -->
    <!-- photoswipe -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>
            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <!--<button class="pswp__button pswp__button&#45;&#45;share" title="Share"></button>-->
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>
                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>
    @if(Session::has('welcome'))
    <div class="modal fade" tabindex="-1" role="dialog" id="welcome-message" aria-labelledby="trackerLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            {{-- <div class="modal-header">
              <h5 class="modal-title">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div> --}}
            <div class="modal-body text-center py-5">
                <svg style="width: 40px" class="fill-primary mb-3 mx-auto" id="icon-recorded-tick" viewBox="0 0 40 40"><path d="M17 27.556c-.444 0-.889-.112-1.222-.445-.667-.667-.667-1.667 0-2.333l11.889-11.89c.666-.666 1.666-.666 2.333 0 .667.668.667 1.668 0 2.334l-11.889 11.89c-.222.332-.667.444-1.111.444z"></path><path d="M17 27.556c-.444 0-.889-.112-1.222-.445l-5.89-5.889c-.666-.666-.666-1.666 0-2.333.668-.667 1.668-.667 2.334 0l5.89 5.889c.666.666.666 1.666 0 2.333-.223.333-.668.445-1.112.445z"></path><path d="M20 0C9 0 0 9 0 20s9 20 20 20 20-9 20-20S31 0 20 0zm0 35.556c-8.556 0-15.556-7-15.556-15.556S11.444 4.444 20 4.444s15.556 7 15.556 15.556-7 15.556-15.556 15.556z"></path></svg>
              <h3 class="color-primary my-3">Welcome to {{SiteSetting()['website_title']}}</h3>
              <p>Now you can earn cashback at your favourite places to shop online, from travel and tech to fashion and gadgets, thousands of exclusive rates are available. It’s completely safe, secure and there are absolutely no hidden charges.</p>
              {{-- <button type="button" class="btn btn-primary my-3" href="{{route('offers')}}">View Offers</button> --}}

              
            </div>
            {{-- <div class="modal-footer">
              
              <button type="button" class="btn btn-primary" data-dismiss="modal">Okay</button>
            </div> --}}
          </div>
        </div>
      </div>

{{-- <p class="alert
{{ Session::get('alert-class', 'alert-info') }}">{{Session::get('message') }}</p> --}}

@endif
{{--@if(Session::has('welcome'))
    <div class="modal fade" tabindex="-1" role="dialog" id="login-message" aria-labelledby="trackerLabel" aria-hidden="true">
        <div class="modal-dialog login-modal-dialog" role="document">
            <div class="modal-content" style="background: #f9fafd">
                <div class="modal-body text-center py-5">
                    <svg style="width: 40px" class=" fill-primary mb-3 mx-auto" id="icon-recorded-tick" viewBox="0 0 40 40"><path d="M17 27.556c-.444 0-.889-.112-1.222-.445-.667-.667-.667-1.667 0-2.333l11.889-11.89c.666-.666 1.666-.666 2.333 0 .667.668.667 1.668 0 2.334l-11.889 11.89c-.222.332-.667.444-1.111.444z"></path><path d="M17 27.556c-.444 0-.889-.112-1.222-.445l-5.89-5.889c-.666-.666-.666-1.666 0-2.333.668-.667 1.668-.667 2.334 0l5.89 5.889c.666.666.666 1.666 0 2.333-.223.333-.668.445-1.112.445z"></path><path d="M20 0C9 0 0 9 0 20s9 20 20 20 20-9 20-20S31 0 20 0zm0 35.556c-8.556 0-15.556-7-15.556-15.556S11.444 4.444 20 4.444s15.556 7 15.556 15.556-7 15.556-15.556 15.556z"></path></svg>
                    <h4 class="my-3">Welcome back {{Auth::user()->first_name}} {{Auth::user()->last_name}}.</h4>
                </div>
            </div>
        </div>
    </div>
    @endif--}}

    <!-- photoswipe / end -->
    @include('layouts.frontend.includes.footer_scripts')
    @stack('scripts')
</body>

</html>