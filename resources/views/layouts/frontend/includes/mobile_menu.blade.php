@php

$menu = Harimayco\Menu\Models\Menus::where('name','Main Menu')->first();
 @endphp
<div class="mobilemenu">
    <div class="mobilemenu__backdrop"></div>
    <div class="mobilemenu__body">
        <div class="mobilemenu__header">
            <div class="mobilemenu__title">Menu</div>
            <button type="button" class="mobilemenu__close">
                <svg width="20px" height="20px">
                    <use xlink:href="{{asset('frontend/images/sprite.svg')}}#cross-20"></use>
                </svg>
            </button>
        </div>
        <div class="mobilemenu__content">
            <ul class="mobile-links mobile-links--level--0" data-collapse data-collapse-opened-class="mobile-links__item--open">
                @if(Auth::check())
                <li class="mobile-links__item" data-collapse-item>
                    <div class="mobile-links__item-title">
                        <a href="@if(Auth::user()->hasRole('user')){{route('account.dashboard')}}@elseif(Auth::user()->hasRole('admin')){{route('admin.home.index')}}@endif" class="mobile-links__item-link">Account</a>
                        <button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
                            <svg class="mobile-links__item-arrow" width="12px" height="7px">
                                <use xlink:href="{{asset('frontend/images/sprite.svg')}}#arrow-rounded-down-12x7"></use>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mobile-links__item-sub-links" data-collapse-content>
                        <ul class="mobile-links mobile-links--level--1">
                            <li class="mobile-links__item" data-collapse-item>
                                <div class="mobile-links__item-title">
                                    <a href="{{route('account.dashboard')}}" class="mobile-links__item-link">Dashboard</a>
                                </div>
                            </li>
                            @if(Auth::user()->hasRole('user'))
                            <li class="mobile-links__item" data-collapse-item>
                                <div class="mobile-links__item-title">
                                    <a href="{{route('account.profile')}}" class="mobile-links__item-link">Profile</a>
                                </div>
                            </li>
                            <li class="mobile-links__item" data-collapse-item>
                                <div class="mobile-links__item-title">
                                    <a href="{{route('account.cashback')}}" class="mobile-links__item-link">Earning</a>
                                </div>
                            </li>
                            <li class="mobile-links__item" data-collapse-item>
                                <div class="mobile-links__item-title">
                                    <a href="{{route('account.clicks')}}" class="mobile-links__item-link">Clicks</a>
                                </div>
                            </li>
                            <li class="mobile-links__item" data-collapse-item>
                                <div class="mobile-links__item-title">
                                    <a href="{{route('account.withdraw.index')}}" class="mobile-links__item-link">Withdraw</a>
                                </div>
                            </li>
                            <li class="mobile-links__item" data-collapse-item>
                                <div class="mobile-links__item-title">
                                    <a href="{{route('account.payment_details')}}" class="mobile-links__item-link">Payment Method</a>
                                </div>
                            </li>
                            <li class="mobile-links__item" data-collapse-item>
                                <div class="mobile-links__item-title">
                                    <a href="{{route('account.statement')}}" class="mobile-links__item-link">Statement</a>
                                </div>
                            </li>
                            <li class="mobile-links__item" data-collapse-item>
                                <div class="mobile-links__item-title">
                                    <a href="{{route('account.change_password')}}" class="mobile-links__item-link">Change Password</a>
                                </div>
                            </li>
                            @endif
                            <li class="mobile-links__item" data-collapse-item>
                                <div class="mobile-links__item-title">
                                    <a href="" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"class="mobile-links__item-link">Logout</a>
                                </div>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                    </form>
                            </li>
                        </ul>
                    </div>
                   
                </li>
                @endif
                @foreach($menu->items as $item)
                <li class="mobile-links__item" data-collapse-item>
                    <div class="mobile-links__item-title">
                        <a href="{{$item->link}}" class="mobile-links__item-link">{{$item->label}}</a>
                    </div>                  
                </li>
                @endforeach
                {{-- <li class="mobile-links__item" data-collapse-item>
                    <div class="mobile-links__item-title">
                        <a href="" class="mobile-links__item-link">Categories</a>
                        <button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
                            <svg class="mobile-links__item-arrow" width="12px" height="7px">
                                <use xlink:href="{{asset('frontend/images/sprite.svg')}}#arrow-rounded-down-12x7"></use>
                            </svg>
                        </button>
                    </div>
                    <div class="mobile-links__item-sub-links" data-collapse-content>
                        <ul class="mobile-links mobile-links--level--1">
                            <li class="mobile-links__item" data-collapse-item>
                                <div class="mobile-links__item-title">
                                    <a href="" class="mobile-links__item-link">Power Tools</a>
                                    <button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
                                        <svg class="mobile-links__item-arrow" width="12px" height="7px">
                                            <use xlink:href="{{asset('frontend/images/sprite.svg')}}#arrow-rounded-down-12x7"></use>
                                        </svg>
                                    </button>
                                </div>
                                <div class="mobile-links__item-sub-links" data-collapse-content>
                                    <ul class="mobile-links mobile-links--level--2">
                                        <li class="mobile-links__item" data-collapse-item>
                                            <div class="mobile-links__item-title">
                                                <a href="" class="mobile-links__item-link">Engravers</a>
                                            </div>
                                        </li>
                                        <li class="mobile-links__item" data-collapse-item>
                                            <div class="mobile-links__item-title">
                                                <a href="" class="mobile-links__item-link">Wrenches</a>
                                            </div>
                                        </li>
                                        <li class="mobile-links__item" data-collapse-item>
                                            <div class="mobile-links__item-title">
                                                <a href="" class="mobile-links__item-link">Wall Chaser</a>
                                            </div>
                                        </li>
                                        <li class="mobile-links__item" data-collapse-item>
                                            <div class="mobile-links__item-title">
                                                <a href="" class="mobile-links__item-link">Pneumatic Tools</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="mobile-links__item" data-collapse-item>
                                <div class="mobile-links__item-title">
                                    <a href="" class="mobile-links__item-link">Machine Tools</a>
                                    <button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
                                        <svg class="mobile-links__item-arrow" width="12px" height="7px">
                                            <use xlink:href="{{asset('frontend/images/sprite.svg')}}#arrow-rounded-down-12x7"></use>
                                        </svg>
                                    </button>
                                </div>
                                <div class="mobile-links__item-sub-links" data-collapse-content>
                                    <ul class="mobile-links mobile-links--level--2">
                                        <li class="mobile-links__item" data-collapse-item>
                                            <div class="mobile-links__item-title">
                                                <a href="" class="mobile-links__item-link">Thread Cutting</a>
                                            </div>
                                        </li>
                                        <li class="mobile-links__item" data-collapse-item>
                                            <div class="mobile-links__item-title">
                                                <a href="" class="mobile-links__item-link">Chip Blowers</a>
                                            </div>
                                        </li>
                                        <li class="mobile-links__item" data-collapse-item>
                                            <div class="mobile-links__item-title">
                                                <a href="" class="mobile-links__item-link">Sharpening Machines</a>
                                            </div>
                                        </li>
                                        <li class="mobile-links__item" data-collapse-item>
                                            <div class="mobile-links__item-title">
                                                <a href="" class="mobile-links__item-link">Pipe Cutters</a>
                                            </div>
                                        </li>
                                        <li class="mobile-links__item" data-collapse-item>
                                            <div class="mobile-links__item-title">
                                                <a href="" class="mobile-links__item-link">Slotting machines</a>
                                            </div>
                                        </li>
                                        <li class="mobile-links__item" data-collapse-item>
                                            <div class="mobile-links__item-title">
                                                <a href="" class="mobile-links__item-link">Lathes</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li> --}}
                
              
            </ul>
        </div>
    </div>
</div>