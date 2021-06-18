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
    .slider-store-name{
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 18px;
    }
    .suggestions__item.selected{background-color:#faf2e2}
    
    #accordion .btn.focus, .btn:focus {
    box-shadow: none;
}
    </style>
    <!-- site -->
    @isset($settings['theme_skin'])
        @if($settings['theme_skin']=='custom')
            @include('layouts.frontend.includes.colors')
        @endif
    @endisset
    
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
    <!-- photoswipe / end -->
    @include('layouts.frontend.includes.footer_scripts')
    @stack('scripts')
</body>

</html>