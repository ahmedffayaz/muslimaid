@php $menu = Harimayco\Menu\Models\Menus::where('name','Main Menu')->first(); @endphp

<div class="mobilemenu">
    <div class="mobilemenu__backdrop"></div>
    <div class="mobilemenu__body">
        <div class="mobilemenu__header">
            <div class="mobilemenu__title">Menu</div>
            <button type="button" class="mobilemenu__close">
                <svg width="20px" height="20px">
                    <use xlink:href="{{ asset('frontend/images/sprite.svg') }}#cross-20"></use>
                </svg>
            </button>
        </div>
        <div class="mobilemenu__content">
            <ul class="mobile-links mobile-links--level--0" data-collapse data-collapse-opened-class="mobile-links__item--open">

                @php $categories = getCategories(); @endphp

                <li class="mobile-links__item" data-collapse-item>
                    <div class="mobile-links__item-title">
                        <a href="" class="mobile-links__item-link">Categories</a>
                        <button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
                            <svg class="mobile-links__item-arrow" width="12px" height="7px">
                                <use xlink:href="{{ asset('frontend/images/sprite.svg') }}#arrow-rounded-down-12x7"></use>
                            </svg>
                        </button>
                    </div>
                    <div class="mobile-links__item-sub-links" data-collapse-content>
                        <ul class="mobile-links mobile-links--level--1">
                            @foreach ($categories as $category)
                                <li class="mobile-links__item" data-collapse-item>
                                    <div class="mobile-links__item-title">
                                        <a href="" class="mobile-links__item-link">{{ $category->name }}</a>
                                        @if (count($category->childs))
                                            <button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
                                                <svg class="mobile-links__item-arrow" width="12px" height="7px">
                                                    <use xlink:href="{{ asset('frontend/images/sprite.svg') }}#arrow-rounded-down-12x7"></use>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    @if (count($category->childs))
                                        @include('frontend.components.responsive-subcategories-dropdown', ['childs' => $category->childs])
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>

                @if (!Auth::check())
                    <li class="mobile-links__item" data-collapse-item>
                        <div class="mobile-links__item-title">
                            <a href="{{ route('login') }}" class="mobile-links__item-link">Login</a>
                            <span>
                                <div style="border-left:1px solid rgba(0,0,0,0.1); height: 48px;"></div>
                            </span>
                            <span class="mr-3 ml-4">
                                <div class="mobile-links__item-title">
                                    <a href="{{ route('register') }}" class="mobile-links__item-link">Register</a>
                                </div>
                            </span>
                        </div>
                    </li>
                @endif

                @foreach ($menu->items as $item)
                    <li class="mobile-links__item" data-collapse-item>
                        <div class="mobile-links__item-title">
                            <a href="{{ $item->link }}" class="mobile-links__item-link">{{ $item->label }}</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
