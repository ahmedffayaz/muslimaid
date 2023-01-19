@extends('frontend.layouts.app')
@section('content')
<style>/*  bhoechie tab */
  [dir=ltr] .product-card--layout--horizontal .product-card__actions {
    left: 90px;
}.product-card--layout--horizontal .product-card__image {
    width: 90px;
    padding: 14px;
}
.product-card__name a:hover{
    color: black;
}
</style>
<div class="block block-product-columns mt-5 mb-3">
    <div class="container">
        <div class="row">
           
            <div class="col-lg-12">
                <h2>All Stores</h2>
                <p>All your favourite brands in one place, explore department stores, technology, fashion & more, right here from A to Z.</p>
            </div>
        </div>
    </div>
</div>
<div class="block block-product-columns">
    <div class="container">
        <div class="row">
           
            @foreach(range('A', 'Z') as $letter)
            @if($groups->has($letter))
            
            <div class="col-lg-3 mt-5">
                <div class="block-header">
                    <h3 class="block-header__title"> {{$letter}}</h3>
                    <div class="block-header__divider"></div>
                    <ul class="block-header__groups-list">
                        <li><a href="{{route('all_stores_of_letter',$letter)}}" type="button" class="block-header__group color-primary">View All</a></li>
                    </ul>
                </div>
                <div class="block-product-columns__column">
                    <div class="block-product-columns__item">
                        <div class="product-card product-card--hidden-actions product-card--layout--horizontal d-block p-3">
                    @foreach ($groups[$letter]->take(15) as $store)
                        
                    
                    
                            {{-- <div class="product-card__image product-image">
                                <a href="{{route('store.show',$store->slug)}}" class="product-image__body">
                                    <img class="product-image__img"   @if($store->logo->first())
                                    @if($store->logo->first()->is_fake)
                                        src="{{asset('frontend/images/logos/'.$store->logo->first()->image)}}"
                                    @else
                                        src="{{asset('storage/stores/images/'.$store->logo->first()->image)}}"
                                    @endif
                                @else
                                    src="{{asset('frontend/images/products/product-16.jpg')}}" 
                                @endif alt="">
                                </a>
                            </div> --}}
                            <div class="product-card__info py-1 color-primary">
                                <div class="product-card__name">
                                    <a href="{{route('store.show',$store->slug)}}">{{$store->name}}</a>
                                </div>
                                
                            </div>
                            {{-- <div class="product-card__actions">
                               
                                <div class="product-card__prices">
                                    @if($store->cashback)
                                        @if($store->cashback->type=='fixed'){{$store->cashback->currency}}
                                        @endif
                                        @if($store->custom_cashback_percentage)
                                        {{($store->custom_cashback_percentage/100)*$store->cashback->sale_commission}}
                                        @else
                                        {{(SiteSetting()['cashback_percentage']/100)*$store->cashback->sale_commission}}
                                        @endif
                                        @if($store->cashback->type=='percentage')%@endif Cashback
                                    @endif
                                </div>
                             
                            </div> --}}
                        
                    @endforeach
                </div>
            </div>
                </div>
            </div>
            @endif
            @endforeach

            
         
        </div>
        <div class="row">
           
            @foreach(range('0', '9') as $letter)
            @if($groups->has($letter))
            
            <div class="col-lg-3 mt-5">
                <div class="block-header">
                    <h3 class="block-header__title"> {{$letter}}</h3>
                    <div class="block-header__divider"></div>
                    <ul class="block-header__groups-list">
                        <li><a href="{{route('all_stores_of_letter',$letter)}}" type="button" class="block-header__group color-primary">View All</a></li>
                    </ul>
                </div>
                <div class="block-product-columns__column">
                    <div class="block-product-columns__item">
                        <div class="product-card product-card--hidden-actions product-card--layout--horizontal d-block p-3">
                    @foreach ($groups[$letter] as $store)
                        
                    
                    
                            {{-- <div class="product-card__image product-image">
                                <a href="{{route('store.show',$store->slug)}}" class="product-image__body">
                                    <img class="product-image__img"   @if($store->logo->first())
                                    @if($store->logo->first()->is_fake)
                                        src="{{asset('frontend/images/logos/'.$store->logo->first()->image)}}"
                                    @else
                                        src="{{asset('storage/stores/images/'.$store->logo->first()->image)}}"
                                    @endif
                                @else
                                    src="{{asset('frontend/images/products/product-16.jpg')}}" 
                                @endif alt="">
                                </a>
                            </div> --}}
                            <div class="product-card__info py-1 color-primary">
                                <div class="product-card__name">
                                    <a href="{{route('store.show',$store->slug)}}">{{$store->name}}</a>
                                </div>
                                
                            </div>
                            {{-- <div class="product-card__actions">
                               
                                <div class="product-card__prices">
                                    @if($store->cashback)
                                        @if($store->cashback->type=='fixed'){{$store->cashback->currency}}
                                        @endif
                                        @if($store->custom_cashback_percentage)
                                        {{($store->custom_cashback_percentage/100)*$store->cashback->sale_commission}}
                                        @else
                                        {{(SiteSetting()['cashback_percentage']/100)*$store->cashback->sale_commission}}
                                        @endif
                                        @if($store->cashback->type=='percentage')%@endif Cashback
                                    @endif
                                </div>
                             
                            </div> --}}
                        
                    @endforeach
                </div>
            </div>
                </div>
            </div>
            @endif
            @endforeach

            
         
        </div>

      
            <h4 class="text-capitalize mt-4 d-none">Other</h4>
        
            
            
            <div class="row">
                <div class="col-12">
                    <div class="block">
                        <div class="block-header">
                        
                        </div>
                        <div class="products-view">
                            <div class="products-view__list products-list scrolling-pagination" data-layout="grid-5-full" data-with-features="false" data-mobile-grid-columns="2">
                                <div class="products-list__body ">
                                    @foreach($groups as $letter =>$stores)
                                    @if(preg_match('/[^a-z0-9]/i', $letter))
                                    @foreach ($stores as $store)
                                    <div class="products-list__item">
                                        <a href="{{route('store.show',$store->slug)}}">{{$store->name}}</a>
                                    
                                    </div>
                                    @endforeach
                                    @endif
                                    @endforeach
                                    {{-- {!! $stores->links()!!}  --}}
                                </div>
                            </div>
                        
                        </div>
                    </div>
                    
                </div>
            </div>
            

            
         
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
    $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });
});
</script>    
<script type="text/javascript">
    $('ul.pagination').hide();
    $(function() {
        $('.scrolling-pagination').jscroll({
            autoTrigger: true,
            loadingHtml: `<div class="form-group text-center mt-4">
                                        <button class="btn btn-light text-primary btn-loading btn-xl btn-svg-icon">
                                            <svg width="16px" height="16px">
                                                <use xlink:href="{{asset('frontend/images/sprite.svg')}}#quickview-16"></use>
                                            </svg>
                                        </button>
                                    </div>`,
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.scrolling-pagination',
            callback: function() {
                $('ul.pagination').remove();
            }
        });
    });
</script>
@endpush