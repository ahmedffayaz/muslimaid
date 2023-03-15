<div>
    <form class="search__form" action="{{ route('search') }}">
        <input wire:model="search" type="text" class="search__input" name="search" placeholder="Search stores..."
            value="" aria-label="Store search" type="text" autocomplete="off" />
        <button class="search__button search__button--type--submit" type="submit">
            <svg width="20px" height="20px">
                <use xlink:href="{{ asset('frontend/images/sprite.svg') }}#search-20"></use>
            </svg>
        </button>
        <button class="search__button search__button--type--close min-search-icon" type="button">
            <svg width="20px" height="20px">
                <use xlink:href="{{ asset('frontend/images/sprite.svg') }}#cross-20"></use>
            </svg>
        </button>
        <div class="search__border"></div>
    </form>
    <div class="suggestions--location--header search_box_div">
        <ul class="suggestions__list sugesstion_dropdown_list">
            @if (count($stores))
                @foreach ($stores->take(8) as $key => $store)
                    @if($store->status == 'active')
                        <li class="suggestions__item @if ($key == 0) selected @endif">
                            <div class="suggestions__item-image product-image">
                                <div class="product-image__body">
                                    <img class="product-image__img" alt="{{ $store->name }}" src="{{ $store->logo->first() && !$store->logo->first()->is_fake ? asset($store->logo->first()->image) : asset('frontend/images/' . ($store->logo->first() ? 'logos/'.$store->logo->first()->image : 'products/product-16.jpg')) }}">
                                </div>
                            </div>
                            <div class="suggestions__item-info">
                                <a href="{{ route('store.show', $store->slug) }}"
                                    class="suggestions__item-name">{!! $store->name !!}</a>
                            </div>
                            <div class="suggestions__item-price">
                                {{ $store->getCashback() }}
                            </div>
                        </li>
                    @endif
                @endforeach
            @endif
        </ul>
    </div>
</div>

@push('scripts')
    <script>
        $(document).on("click", function(event) {
            var $trigger = $(".search_box_div");
            if ($trigger !== event.target && !$trigger.has(event.target).length) {
                $(".sugesstion_dropdown_list").slideUp("fast");
            }
        });
    </script>
@endpush
