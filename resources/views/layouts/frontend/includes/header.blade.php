@php

$menu = Harimayco\Menu\Models\Menus::where('name','Main Menu')->first();

@endphp

<header class="site__header d-lg-block d-none">
    <div class="site-header">
        <div class="site-header__middle container">
            <div class="site-header__logo">
                <a href="{{url('/')}}">
                    <!-- logo -->
                    <img width="100%" src="@if(isset($settings['website_logo']) && $settings['website_logo']!='default.png'){{asset('storage/dashboard/images/logo/'.$settings['website_logo'])}}@else{{asset('admin-dashboard/images/logo.png')}}@endif" alt="">
                   
                    <!-- logo / end -->
                </a>
            </div>
            @include('frontend.components.categories-dropdown')
            <div class="site-header__search ml-lg-5">
                <div class="search search--location--header ">
                    <div class="search__body bg-white">
                        @livewire('search-stores')
                    </div>
                </div>
            </div>
            <div class="site-header__phone">
                @auth
                {{-- <div class="site-header__phone-title">Balance</div> --}}
                <div class="site-header__phone-number"><span class="currency">{{ currency() }} </span>{{Auth::user()->availableBalance()}}</div>
                @endauth

            </div>
        </div>
        <div class="site-header__nav-panel">
            <!-- data-sticky-mode - one of [pullToShow, alwaysOnTop] -->
            <div class="nav-panel nav-panel--sticky" data-sticky-mode="pullToShow">
                <div class="nav-panel__container container">
                    <div class="nav-panel__row">
                       
                        <!-- .nav-links -->
                        <div class="nav-panel__nav-links nav-links">
                            <ul class="nav-links__list">
                                @foreach($menu->items as $item)

                                <li class="nav-links__item  nav-links__item--has-submenu ">
                                    <a class="nav-links__item-link" href="{{$item->link}}">
                                        <div class="nav-links__item-body">{{$item->label}}</div>
                                    </a>
                                    @if( $item['child']->count() )

                                    <div class="nav-links__submenu nav-links__submenu--type--menu">
                                        <!-- .menu -->
                                        <div class="menu menu--layout--classic ">
                                            <div class="menu__submenus-container"></div>
                                            <ul class="menu__list">
                                                @foreach( $item['child'] as $child )
                                                <li class="menu__item">
                                                    <!-- This is a synthetic element that allows to adjust the vertical offset of the submenu using CSS. -->
                                                    <div class="menu__item-submenu-offset"></div>
                                                    <a class="menu__item-link" href="{{ $child['link'] }}">
                                                        {{ $child['label'] }}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <!-- .menu / end -->
                                    </div>

                                    @endif
                                </li>
                                @endforeach

                            </ul>
                        </div>
                        <!-- .nav-links / end -->
                        <div class="nav-panel__indicators">

                            @if(Auth::check())
                            <div class="indicator indicator--trigger--hover">
                                <a href="@if(Auth::user()->hasRole('user')){{route('account.dashboard')}}@elseif(Auth::user()->hasRole('admin')){{route('admin.home.index')}}@endif" class="indicator__button">
                                    <span class="indicator__area">
                                        <svg width="20px" height="20px">
                                            <use xlink:href="{{asset('frontend/images/sprite.svg')}}#person-20"></use>
                                        </svg>
                                    </span>
                                </a>
                                <div class="indicator__dropdown">
                                    <div class="account-menu">
                                        <div class="account-menu__divider"></div>
                                        <a onclick="" class="account-menu__user">
                                            <div class="account-menu__user-avatar">
                                                <img src="{{asset('admin-dashboard/images/avatar.png')}}"
                                     alt="store logo" class="" style="max-width:50px;max-height:50px"/>
                                            </div>
                                            <div class="account-menu__user-info">
                                                <div class="account-menu__user-name">{{\Auth::user()->first_name}} {{\Auth::user()->last_name}}</div>
                                                <div class="account-menu__user-email">{{\Auth::user()->email}}</div>
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
                            @else
                            <a class="nav-links__item-link" href="{{route('account.login')}}">
                                <div class="nav-links__item-body">
                                    Login
                                </div>
                            </a>

                            <a class="nav-links__item-link" href="{{route('account.register')}}">
                                <div class="nav-links__item-body">
                                    Register
                                </div>
                            </a>
                            @endif


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</header>