<ul class="suggestions__list">
    @if ($stores->count())
        @foreach ($stores->take(8) as $key => $store)
            <li class="suggestions__item @if ($key == 0) selected @endif">
                <div class="suggestions__item-image product-image">
                    <div class="product-image__body">
                        <img class="product-image__img" alt=""
                            @if ($store->logo->first()) 
                                @if ($store->logo->first()->is_fake)
                                    src="{{ asset('frontend/images/logos/' . $store->logo->first()->image) }}"
                                @else
                                    src="{{ asset('storage/stores/images/' . $store->logo->first()->image) }}"
                                @endif
                            @else 
                                src="{{ asset('frontend/images/products/product-16.jpg') }}" 
                            @endif>
                    </div>
                </div>
                <div class="suggestions__item-info">
                    <a href="{{ route('store.show', $store->slug) }}" class="suggestions__item-name">{!! textHighlight($store->name, $term) !!}</a>
                </div>
                <div class="suggestions__item-price">
                    @if ($store->custom_cashback_percentage)
                        @if ($store->cashback->type == 'fixed')
                            {{ $store->cashback->currency }}
                        @endif
                        {{ ($store->custom_cashback_percentage / 100) * $store->cashback->sale_commission }}
                        @if ($store->cashback->type == 'percentage')%@endif
                    @else
                        @if ($store->cashback->type == 'fixed')
                            {{ $store->cashback->currency }}
                        @endif
                        {{ (SiteSetting()['cashback_percentage'] / 100) * $store->cashback->sale_commission }}
                        @if ($store->cashback->type == 'percentage')%@endif
                    @endif
                    Cashback
                </div>
            </li>
        @endforeach
    @else
        <li class="suggestions__item">
            <div class="suggestions__item-info">
                No results found
            </div>
        </li>
    @endif
</ul>
