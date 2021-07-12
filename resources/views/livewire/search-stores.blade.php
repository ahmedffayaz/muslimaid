<div>
   
    
        <form class="search__form" action="{{route('search')}}">
        <input wire:model="search" type="text" class="search__input" name="search"  placeholder="Search stores..." value="" aria-label="Store search" type="text" autocomplete="off"/>
        <button class="search__button search__button--type--submit" type="submit">
            <svg width="20px" height="20px">
                <use xlink:href="{{asset('frontend/images/sprite.svg')}}#search-20"></use>
            </svg>
        </button>
        <div class="search__border"></div>
        </form>

    <div class="suggestions--location--header search_box_div">
        <ul class="suggestions__list sugesstion_dropdown_list">
            @if(count($stores))
            @foreach($stores->take(8) as $key=>$store)
            <li class="suggestions__item @if($key==0) selected @endif"  >
                <div class="suggestions__item-image product-image">
                    <div class="product-image__body">
                        <img class="product-image__img"  @if($store->logo->first())
                        @if($store->logo->first()->is_fake)
                            src="{{asset('frontend/images/logos/'.$store->logo->first()->image)}}"
                        @else
                            src="{{asset('storage/stores/images/'.$store->logo->first()->image)}}"
                        @endif
                        @else
                            src="{{asset('frontend/images/products/product-16.jpg')}}" 
                        @endif alt="">
                    </div>
                </div>
                <div class="suggestions__item-info">
                    <a href="{{route('store.show',$store->slug)}}" class="suggestions__item-name">{!!$store->name!!}</a>
                </div>
                <div class="suggestions__item-price">
                    @if($store->cashback->type=='fixed'){{$store->cashback->currency}}

                    @endif
                    @if($store->custom_cashback_percentage)
                    {{($store->custom_cashback_percentage/100)*$store->cashback->sale_commission}}
                    @else
                    {{(SiteSetting()['cashback_percentage']/100)*$store->cashback->sale_commission}}
                    @endif

                    @if($store->cashback->type=='percentage')%@endif Cashback
                </div>
            </li>
            @endforeach
                {{-- @if($stores->count()>8)
                    <li class="suggestions__item">
                        <div class="suggestions__item-info">
                        {{$stores->count()-8}}+ more stores
                        
                        </div>
                    </li>
                @endif --}}
            {{-- @else 
            <li class="suggestions__item">
                <div class="suggestions__item-info">
                   No results found
                  
                </div>
            </li> --}}
            @endif
        </ul>
    </div>
    
</div>

@push('scripts')
    <script>
        $(document).on("click", function(event){
            var $trigger = $(".search_box_div");
            if($trigger !== event.target && !$trigger.has(event.target).length){
                $(".sugesstion_dropdown_list").slideUp("fast");
            }         
        });
    </script>
@endpush