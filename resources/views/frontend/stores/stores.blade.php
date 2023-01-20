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
                                <div class="products-view__list products-list scrolling-pagination" data-layout="grid-5-full" data-with-features="false"
                                    data-mobile-grid-columns="2">
                                    <div class="products-list__body ">
                                        @foreach ($locations as $store)
                                            @include('frontend.layouts.includes.stores')
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
