<div class="shop-layout__sidebar">
    <div class="block block-sidebar">
        @isset($store)
            <div class="block-sidebar__item">
                <div class="widget-categories widget-categories--location--shop widget">
                    <h4 class="widget__title">Store Stats</h4>
                    <ul class="widget-categories__list" data-collapse data-collapse-opened-class="widget-categories__item--open">
                        <li class="widget-categories__item mb-4" data-toggle="popover" title="98% tracked"
                            data-content="On average, 98% transactions at this retailer track in to your Activity within less than 24 hours">
                            <div class="widget-categories__row">
                                <div class="col-3 pl-0">
                                    <svg class="fill-primary" id="icon-stats-reliability" viewBox="0 0 24 24">
                                        <path
                                            d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.6 0 12 0zm0 22.5C6.2 22.5 1.5 17.8 1.5 12S6.2 1.5 12 1.5 22.5 6.2 22.5 12 17.8 22.5 12 22.5z">
                                        </path>
                                        <path
                                            d="M16.8 6.5c-1 0-1.7.8-1.7 1.7 0 .8.5 1.5 1.3 1.7.1.3.3.6.7.6.4 0 .7-.3.7-.7v-.2c.5-.3.8-.8.8-1.4 0-.9-.8-1.7-1.8-1.7zM8.7 7.8c-.5.1-.9.2-1.3.5-.3.3-.4.8-.1 1.1.1.2.4.3.6.3.2 0 .3 0 .4-.1.2-.1.3-.2.5-.2.4 0 .7-.4.7-.8 0-.5-.4-.8-.8-.8zm-2.6 6c-.4 0-.8.3-.8.7v1.1c0 .4.3.7.7.7.4 0 .7-.4.7-.8v-1c.2-.4-.2-.7-.6-.7zm.7-3.5c-.4-.1-.8.1-.9.5-.1.3-.2.7-.3 1-.1.4.2.8.6.9h.1c.4 0 .7-.2.7-.6.1-.3.1-.6.2-.9.3-.3 0-.7-.4-.9zm5 .2c-.1-.4-.3-.7-.4-1-.2-.4-.6-.5-1-.3s-.5.6-.3 1c.1.3.2.5.4.8.1.3.4.5.7.5h.3c.2-.1.5-.6.3-1zm.8 2.4c-.1-.4-.6-.6-1-.5-.4.1-.6.6-.5 1 .1.4.3.7.4 1 .1.3.4.4.7.4.1 0 .2 0 .3-.1.4-.2.5-.6.3-1 0-.2-.1-.5-.2-.8zm4.3-1.3c-.4-.1-.8.2-.9.6-.1.3-.1.6-.2.9-.1.4.1.8.5 1h.2c.3 0 .6-.2.7-.5.1-.3.2-.7.3-1 .1-.5-.2-.9-.6-1zm-2.2 3c-.2.1-.4.2-.6.2-.4 0-.7.3-.7.8 0 .4.3.7.8.7s.9-.1 1.3-.3c.4-.2.5-.7.3-1-.3-.5-.7-.6-1.1-.4z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="col-9 pl-0">
                                    <h6>Tracking Reliability</h6>
                                    <span class="color-primary">98% tracked</span>
                                </div>
                            </div>
                        </li>
                        <li class="widget-categories__item mb-4" data-toggle="popover" title="Less than 24 hours"
                            data-content="On average, purchases at this retailer track into your Activity in less than 24 hours.">
                            <div class="widget-categories__row">
                                <div class="col-3 pl-0">
                                    <svg class="fill-primary" id="icon-stats-claims" viewBox="0 0 24 24">
                                        <path
                                            d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.6 0 12 0zm0 22.5C6.2 22.5 1.5 17.8 1.5 12S6.2 1.5 12 1.5 22.5 6.2 22.5 12 17.8 22.5 12 22.5z">
                                        </path>
                                        <path d="M12 8h4v2h-4zm-3 3h7v2H9zm-3 3h10v2H6z"></path>
                                    </svg>
                                </div>
                                <div class="col-9 pl-0">
                                    <h6>Tracking speed</h6>
                                    <span class="color-primary">Less than 24 hours</span>
                                </div>
                            </div>
                        </li>
                        <li class="widget-categories__item" data-toggle="popover" title="Less than 9 months"
                            data-content="On average, cashback for this retailer will take less than 9 months to be confirmed">
                            <div class="widget-categories__row">
                                <div class="col-3 pl-0">
                                    <svg class="fill-primary" id="icon-stats-payment" viewBox="0 0 24 24">
                                        <path
                                            d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.6 0 12 0zm0 22.5C6.2 22.5 1.5 17.8 1.5 12S6.2 1.5 12 1.5 22.5 6.2 22.5 12 17.8 22.5 12 22.5z">
                                        </path>
                                        <path
                                            d="M8.5 14.7l1-.3v-1.8h-1V11h1v-1c0-.9.3-1.7.8-2.2.5-.5 1.3-.8 2.3-.8 1.5 0 2.3.6 2.9 1.5L14 9.6c-.4-.6-.8-.8-1.4-.8-.3 0-.5.1-.7.3-.2.2-.3.5-.3.9v1h3v1.6h-3v1.7h4V16h-7v-1.3z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="col-9 pl-0">
                                    <h6>Payment Speed</h6>
                                    <span class="color-primary">Less than 9 months</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        @endisset

        @isset($store)
            @php $sidebar_stores = similarStores($store); @endphp

            @if ($sidebar_stores->count())
                <div class="block-sidebar__item">
                    <div class="widget-products widget">
                        <h4 class="widget__title">Similar Stores</h4>
                        <div class="widget-products__list">

                            @foreach ($sidebar_stores->take(5) as $item)
                                <a href="{{ route('store.show', $item->slug) }}" class="widget-products__item">
                                    <div class="widget-products__image">
                                        <div class="product-image">
                                            <img class="" alt="logo"
                                                @if ($item->logo->first()) 
                                                    @if ($item->logo->first()->is_fake)
                                                        src="{{ asset('frontend/images/logos/' . $item->logo->first()->image) }}"
                                                    @else
                                                        src="{{ asset('storage/stores/images/' . $item->logo->first()->image) }}" 
                                                    @endif
                                                @else 
                                                    src="{{ asset('frontend/images/products/product-16.jpg') }}" 
                                                @endif>
                                        </div>
                                    </div>
                                    <div class="widget-products__info align-self-center">
                                        <div class="widget-products__name text-dark">
                                            {{ $item->name }}
                                        </div>
                                        <div class="widget-products__prices">
                                            @if ($item->cashback)
                                                @if ($item->custom_cashback_percentage)
                                                    @if ($item->cashback->type == 'fixed') {{ $item->cashback->currency }} @endif
                                                    {{ ($item->custom_cashback_percentage / 100) * $item->cashback->sale_commission }}
                                                    @if ($item->cashback->type == 'percentage') % @endif
                                                @else
                                                    @if ($item->cashback->type == 'fixed') {{ $item->cashback->currency }} @endif
                                                    {{ (SiteSetting()['cashback_percentage'] / 100) * $item->cashback->sale_commission }}
                                                    @if ($item->cashback->type == 'percentage') % @endif
                                                @endif
                                            @endif
                                            Cashback
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="block-sidebar__item">
                <div class="widget-products widget">
                    <h4 class="widget__title">Featured Stores</h4>
                    <div class="widget-products__list">
                        @php $sidebar_stores = sidebarStores(); @endphp

                        @if ($sidebar_stores->count() == 0)
                            <p>Not enough data</p>
                        @endif

                        @foreach ($sidebar_stores->take(5) as $item)
                            <a href="{{ route('store.show', $item->slug) }}" class="widget-products__item">
                                <div class="widget-products__image">
                                    <div class="product-image">
                                        <img class="" alt="logo"
                                            @if ($item->logo->first()) 
                                                @if ($item->logo->first()->is_fake)
                                                    src="{{ asset('frontend/images/logos/' . $item->logo->first()->image) }}"
                                                @else
                                                    src="{{ asset('storage/stores/images/' . $item->logo->first()->image) }}" 
                                                @endif
                                            @else 
                                                src="{{ asset('frontend/images/products/product-16.jpg') }}" 
                                            @endif>
                                    </div>
                                </div>
                                <div class="widget-products__info align-self-center">
                                    <div class="widget-products__name text-dark">
                                        {{ $item->name }}
                                    </div>
                                    <div class="widget-products__prices">
                                        @if ($item->custom_cashback_percentage)
                                            @if ($item->cashback->type == 'fixed') {{ $item->cashback->currency }} @endif
                                            {{ ($item->custom_cashback_percentage / 100) * $item->cashback->sale_commission }}
                                            @if ($item->cashback->type == 'percentage') % @endif
                                        @else
                                            @if ($item->cashback->type == 'fixed') {{ $item->cashback->currency }} @endif
                                            {{ (SiteSetting()['cashback_percentage'] / 100) * $item->cashback->sale_commission }}
                                            @if ($item->cashback->type == 'percentage') % @endif
                                        @endif
                                        Cashback
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endisset
    </div>
</div>
