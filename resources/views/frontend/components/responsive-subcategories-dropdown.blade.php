<div class="mobile-links__item-sub-links" data-collapse-content>
    <ul class="mobile-links mobile-links--level--2">
        @foreach ($childs as $child)
            <li class="mobile-links__item" data-collapse-item>
                <div class="mobile-links__item-title">
                    <a href="" class="mobile-links__item-link" href="{{ route('cashabck', $child->slug) }}">
                        {{ $child->name }}
                    </a>
                </div>
                @if (count($child->childs))
                    @include('frontend.components.responsive-subcategories-dropdown', ['childs' => $child->childs])
                @endif
            </li>
        @endforeach
    </ul>
</div>
