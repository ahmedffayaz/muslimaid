
<div class="block mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if ($locations->count())
                    <div class="block">
                        <div class="block categorygrid">
                            <div class="block-header">
                            </div>
                            <div class="products-view">
                                <div class="products-view__list products-list scrolling-pagination"
                                    data-layout="grid-5-full" data-with-features="false" data-mobile-grid-columns="2">
                                    <div class="products-list__body ">
                                        @foreach ($locations as $store)
                                            <div class="products-list__item text-center all_stores {{ $store->name }}_store">
                                                <div class="product-card ">
                                                    <div class="product-card__image product-image">
                                                        <a href="{{ route('store.show', $store->slug) }}"
                                                            class="product-image__body" style="padding-bottom:100px">
                                                            <img class="product-image__img"
                                                                @if ($store->logo->first()) @if ($store->logo->first()->is_fake)
                                                                    src="{{ asset('frontend/images/logos/' . $store->logo->first()->image) }}"
                                                                @else
                                                                    src="{{ asset('storage/stores/images/' . $store->logo->first()->image) }}" @endif
                                                            @else
                                                                src="{{ asset('frontend/images/products/product-16.jpg') }}"
                                                                @endif alt="">
                                                        </a>
                                                    </div>
                                                    <div class="product-card__info">
                                                        <div class="product-card__name">
                                                            <a href="{{ route('store.show', $store->slug) }}">{{ $store->name }}</a>
                                                        </div>
                                                        @if ($store->reviews->count())
                                                            <div class="product-card__rating mx-auto">
                                                                <div class="product-card__rating-stars">
                                                                    <div class="rating">
                                                                        <div class="rating__body">
                                                                            @foreach (range(1, 5) as $index)
                                                                                <svg class="rating__star @if ($index <= $store->reviews->avg('rating')) rating__star--active @endif"
                                                                                    width="13px" height="12px">
                                                                                    <g class="rating__fill">
                                                                                        <use
                                                                                            xlink:href="{{ asset('frontend/images/sprite.svg') }}#star-normal">
                                                                                        </use>
                                                                                    </g>
                                                                                    <g class="rating__stroke">
                                                                                        <use
                                                                                            xlink:href="{{ asset('frontend/images/sprite.svg') }}#star-normal-stroke">
                                                                                        </use>
                                                                                    </g>
                                                                                </svg>

                                                                                <div
                                                                                    class="rating__star rating__star--only-edge @if ($index <= $store->reviews->avg('rating')) rating__star--active @endif">
                                                                                    <div class="rating__fill">
                                                                                        <div class="fake-svg-icon">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="rating__stroke">
                                                                                        <div class="fake-svg-icon">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="product-card__rating-legend">
                                                                    {{ $store->reviews->count() }} Reviews</div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="product-card__actions">
                                                        <div class="product-card__prices">
                                                            {{-- @if ($store->custom_cashback_percentage) --}}
                                                                {{-- @if ($store->cashback->type == 'fixed')
                                                                    {{ $store->cashback->currency }}@endif{{ ($store->custom_cashback_percentage / 100) * $store->cashback->sale_commission }}
                                                                    @if ($store->cashback->type == 'percentage')%
                                                                    @endif
                                                                @else
                                                                    @if ($store->cashback->type == 'fixed'){{$store->cashback->currency}}@endif{{(SiteSetting()['cashback_percentage']/100)*$store->cashback->sale_commission}}@if ($store->cashback->type == 'percentage')%@endif
                                                                @endif --}}
                                                                Cashback
                                                        </div>
                                                        <div class="product-card__prices">
                                                            <div class="distance calculatedDistance"
                                                                id="distance-<?= $store->id ?>">1.4 miles away</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <h2>No Cashback Found</h2>
                @endif
                <input type="hidden" id="destinationLat" value="">
                <input type="hidden" id="destinationLng" value="">
            </div>
        </div>
        {!! $locations->links() !!}
    </div>
</div>
