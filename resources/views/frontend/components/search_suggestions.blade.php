<ul class="suggestions__list">
    @if($stores->count())
    @foreach($stores->take(8) as $key=>$store)
    <li class="suggestions__item @if($key==0) selected @endif"  >
        <div class="suggestions__item-image product-image">
            <div class="product-image__body">
                <img class="product-image__img" @if($store->logo->first())
                src="{{asset('storage/stores/images/'.$store->logo->first()->image)}}"
                @else
                src="{{asset('frontend/images/products/product-16.jpg')}}" 
                @endif alt="">
            </div>
        </div>
        <div class="suggestions__item-info">
            <a href="{{route('store.show',$store->slug)}}" class="suggestions__item-name">{!!textHighlight($store->name,$term)!!}</a>
        </div>
        <div class="suggestions__item-price">
            @if($store->cashback->type=='fixed'){{$store->cashback->currency}} @endif{{$store->cashback->sale_commission}}@if($store->cashback->type=='percentage')%@endif Cashback
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
    @else 
    <li class="suggestions__item">
        <div class="suggestions__item-info">
           No results found
          
        </div>
    </li>
    @endif
</ul>