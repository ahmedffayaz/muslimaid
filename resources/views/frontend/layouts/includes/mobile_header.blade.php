<header class="site__header d-lg-none">
    <!-- data-sticky-mode - one of [pullToShow, alwaysOnTop] -->
    <div class="mobile-header mobile-header--sticky" data-sticky-mode="pullToShow">
        <div class="mobile-header__panel">
            <div class="container">
                <div class="mobile-header__body">
                    <button class="mobile-header__menu-button">
                        <svg width="18px" height="14px">
                            <use xlink:href="{{asset('frontend/images/sprite.svg')}}#menu-18x14"></use>
                        </svg>
                    </button>
                    <a class="mobile-header__logo" href="{{url('/')}}">
                          <!-- logo -->
                    <img width="196px" src="@if(isset($settings['website_logo']) && $settings['website_logo']!='default.png'){{asset('storage/dashboard/images/logo/'.$settings['website_logo'])}}@else{{asset('admin-dashboard/images/logo.png')}}@endif" alt="">
                   
                    <!-- logo / end -->
                    </a>
                    <div class="search search--location--mobile-header mobile-header__search">
                        <div class="search__body">
                            @livewire('search-stores')
                            <div class="search__suggestions suggestions suggestions--location--mobile-header"></div>
                        </div>
                    </div>
                    <div class="mobile-header__indicators">
                        <div class="indicator indicator--mobile-search indicator--mobile d-md-none">
                            <button class="indicator__button">
                                <span class="indicator__area">
                                    <svg width="20px" height="20px">
                                        <use xlink:href="{{asset('frontend/images/sprite.svg')}}#search-20"></use>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    <div class="nav-panel__row">

                        @if(Auth::check())
                <div class="indicator indicator--trigger--hover">

                    <a href="@if(Auth::user()->hasRole('user')){{route('account.dashboard')}}@elseif(Auth::user()->hasRole('admin')){{route('admin.home.index')}}@endif" class="indicator__button">
                        <span class="indicator__area">
                            
                            <svg width="20px" height="20px">
                                <use xlink:href="{{asset('frontend/images/sprite.svg')}}#person-20"></use>
                            </svg>
                        </span>
                    </a>
                        <div class="indicator__dropdown mt-2">
                            <div class="account-menu">
                                <div class="account-menu__divider"></div>
                                <a onclick="" class="account-menu__user">
                                    <div class="account-menu__user-avatar">
                                        <img src="{{asset('admin-dashboard/images/avatar.png')}}" alt="store logo" class="" style="max-width:50px;max-height:50px" />
                                    </div>
                                    <div class="account-menu__user-info">
                                        <div class="account-menu__user-name">{{\Auth::user()->first_name}} {{\Auth::user()->last_name}}</div>
                                        <div class="account-menu__user-email">{{\Auth::user()->email}}</div>
                                            @auth
                                            <div class="account-menu__user-email">Balance: {{ currency() }}</span>{{number_format((float)Auth::user()->availableBalance(), 2, '.', '')}}</div>
                                            @endauth
                                    </div>
                                </a>
                                <div class="account-menu__divider"></div>
                                <ul class="account-menu__links">
                                    @if(Auth::user()->hasRole('user'))
                                    <li><a href="{{route('account.dashboard')}}">Account</a></li>
                                    @endif
                                    @if(Auth::user()->hasRole('admin'))
                                    <li><a href="{{route('admin.home.index')}}">Admin Dashboard</a></li>
                                    @endif

                                </ul>
                                <div class="account-menu__divider"></div>
                                <ul class="account-menu__links">
                                    <li><a href="#" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">Logout</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                </div>
                        {{-- <div class="indicator indicator--mobile d-sm-flex d-none">
                            <a href="wishlist.html" class="indicator__button">
                                <span class="indicator__area">
                                    <svg width="20px" height="20px">
                                        <use xlink:href="{{asset('frontend/images/sprite.svg')}}#heart-20"></use>
                                    </svg>
                                    <span class="indicator__value">0</span>
                                </span>
                            </a>
                        </div> --}}
                        {{-- <div class="indicator indicator--mobile">
                            <a href="cart.html" class="indicator__button">
                                <span class="indicator__area">
                                    <svg width="20px" height="20px">
                                        <use xlink:href="{{asset('frontend/images/sprite.svg')}}#cart-20"></use>
                                    </svg>
                                    <span class="indicator__value">3</span>
                                </span>
                            </a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>