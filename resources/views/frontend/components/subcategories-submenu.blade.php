<div class="menu__submenu">
    <div class="menu menu--layout--classic ">
        <div class="menu__submenus-container"></div>
        <ul class="menu__list">
            @foreach ($childs as $child)
                <li class="menu__item">
                    <div class="menu__item-submenu-offset"></div>
                    <a class="menu__item-link text-capitalize" href="{{ route('cashabck', $child->slug) }}">
                        {{ $child->name }}
                        @if (count($child->childs))
                            <svg class="menu__item-arrow" width="6px" height="9px">
                                <use xlink:href="{{ asset('frontend/images/sprite.svg') }}#arrow-rounded-right-6x9"></use>
                            </svg>
                        @endif
                    </a>
                    @if (count($child->childs))
                        @include('frontend.components.subcategories-submenu', ['childs' => $child->childs])
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
