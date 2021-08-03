@php

$footer_menu_column_1 = Harimayco\Menu\Models\Menus::where('id','2')->first();
$footer_menu_column_2 = Harimayco\Menu\Models\Menus::where('id','3')->first();
$footer_menu_column_3 = Harimayco\Menu\Models\Menus::where('id','4')->first();
$footer_menu_column_4 = Harimayco\Menu\Models\Menus::where('id','5')->first();

@endphp

<footer class="site__footer">
    <div class="site-footer mt-0">
        <div class="container">
            <div class="site-footer__widgets">
                <div class="row">
                    <div class="col-6 col-md-6 col-lg-2">
                        <div class="site-footer__widget footer-links">
                            <h5 class="footer-links__title">{{$footer_menu_column_1->title}}</h5>
                            <ul class="footer-links__list">
                                @isset($footer_menu_column_1)
                                    @foreach($footer_menu_column_1->items as $item)
                                        <li class="footer-links__item"><a href="{{$item->link}}" class="footer-links__link">{{$item->label}}</a></li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-lg-2">
                        <div class="site-footer__widget footer-links">
                            <h5 class="footer-links__title">{{$footer_menu_column_2->title}}</h5>
                            <ul class="footer-links__list">
                                @isset($footer_menu_column_2)
                                    @foreach($footer_menu_column_2->items as $item)
                                        <li class="footer-links__item"><a href="{{$item->link}}" class="footer-links__link">{{$item->label}}</a></li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="site-footer__widget footer-links">
                            <h5 class="footer-links__title">{{$footer_menu_column_3->title}}</h5>
                            <ul class="footer-links__list">
                                @isset($footer_menu_column_3)
                                    @foreach($footer_menu_column_3->items as $item)
                                        <li class="footer-links__item"><a href="{{$item->link}}" class="footer-links__link">{{$item->label}}</a></li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                   
                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="site-footer__widget footer-links">
                            <h5 class="footer-links__title">{{$footer_menu_column_4->title}}</h5>
                            <ul class="footer-links__list">
                                @isset($footer_menu_column_4)
                                    @foreach($footer_menu_column_4->items as $item)
                                        <li class="footer-links__item"><a href="{{$item->link}}" class="footer-links__link">{{$item->label}}</a></li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-4">
                        <div class="site-footer__widget footer-newsletter">
                            <h5 class="footer-newsletter__title">Newsletter</h5>
                            <form action="{{route('newsletter.store')}}" class="footer-newsletter__form" method="POST">
                                @csrf
                                <label class="sr-only" for="footer-newsletter-address">Email Address</label>
                                <input type="text"name="email" class="footer-newsletter__form-input form-control" id="footer-newsletter-address" placeholder="Email Address...">
                                <button type="submit" class="footer-newsletter__form-button btn btn-primary">Subscribe</button>
                            </form>
                            
                            <p class="newsletter-message mt-1 text-success"></p>
                            @if(@isset(SiteSetting()['facebook']) || @isset(SiteSetting()['twitter']) || @isset(SiteSetting()['linkedin']) || @isset(SiteSetting()['instagram'])) 
                            <div class="footer-newsletter__text footer-newsletter__text--social">
                                Follow us on social networks
                            </div>
                            <!-- social-links -->
                          
                            <div class="social-links footer-newsletter__social-links social-links--shape--circle">
                                <ul class="social-links__list">
                                    @isset(SiteSetting()['facebook'])
                                    <li class="social-links__item">
                                        <a class="social-links__link social-links__link--type--facebook" href="{{SiteSetting()['facebook']}}" target="_blank">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    </li>
                                    @endisset
                                    @isset(SiteSetting()['twitter'])
                                    <li class="social-links__item">
                                        <a class="social-links__link social-links__link--type--twitter" href="//{{SiteSetting()['twitter']}}" target="_blank">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    </li>
                                    @endisset
                                    @isset(SiteSetting()['linkedin'])
                                    <li class="social-links__item">
                                        <a class="social-links__link social-links__link--type--linkedin" href="//{{SiteSetting()['linkedin']}}" target="_blank">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    </li>
                                   @endisset
                                   @isset(SiteSetting()['instagram'])
                                    <li class="social-links__item">
                                        <a class="social-links__link social-links__link--type--instagram" href="{{SiteSetting()['instagram']}}" target="_blank">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    </li>
                                    @endisset
                                </ul>
                            </div>
                            @endif
                            <!-- social-links / end -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="site-footer__bottom">
                <div class="site-footer__copyright">
                    <!-- copyright -->
                   {{$settings['footer_text'] ?? ''}}
                    <!-- copyright / end -->
                </div>
                <div class="site-footer__payments">
                    Designed and Developed by <a href="https://therightsw.com" target="_blank">The Right Software</a>
                </div>
            </div>
        </div>
        <div class="totop">
            <div class="totop__body">
                <div class="totop__start"></div>
                <div class="totop__container container"></div>
                <div class="totop__end">
                    <button type="button" class="totop__button">
                        <svg width="13px" height="8px">
                            <use xlink:href="{{asset('frontend/images/sprite.svg')}}#arrow-rounded-up-13x8"></use>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</footer>