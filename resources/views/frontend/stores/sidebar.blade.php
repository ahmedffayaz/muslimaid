<div class="shop-layout__sidebar">
    <div class="block block-sidebar">
        <div class="block-sidebar__item">
            <div class="widget-categories widget-categories--location--shop widget">
                <h4 class="widget__title">Featured Categories</h4>
                <ul class="widget-categories__list" data-collapse data-collapse-opened-class="widget-categories__item--open">
                    @php $sidebar_categories = sidebarCategories(); @endphp
                   @foreach ($sidebar_categories as $category)
                   <li class="widget-categories__item" data-collapse-item>
                    <div class="widget-categories__row">
                        <a href="{{route('cashabck',$category->slug)}}">
                            <svg class="widget-categories__arrow" width="6px" height="9px">
                                <use xlink:href="{{asset('frontend/images/sprite.svg')}}#arrow-rounded-right-6x9"></use>
                            </svg>
                            {{$category->name}}
                        </a>
                        @if(count($category->childs))
                        <button class="widget-categories__expander" type="button" data-collapse-trigger></button>
                        @endif
                    </div>
                    @if(count($category->childs))
                        @include('frontend.stores.sidebar_widget_subcategories',['childs' => $category->childs])
                    @endif
                </li>  
                   @endforeach
                </ul>
            </div>
        </div>
        <div class="block-sidebar__item">
            <div class="widget-products widget">
                <h4 class="widget__title">Featured Stores</h4>
                <div class="widget-products__list">
                    @php $sidebar_stores = sidebarStores(); @endphp
                    @foreach ($sidebar_stores->take(10) as $item)
               
                    <a href="" class="widget-products__item">
                        <div class="widget-products__image">
                            <div class="product-image">
                                {{-- <a href="{{route('store.show',$item->slug)}}" class="mb-2 d-inline-block"> --}}
                                    <img class="" @if($item->logo->first())
                                    src="{{asset('storage/stores/images/'.$item->logo->first()->image)}}"
                                    @else
                                    src="{{asset('frontend/images/products/product-16.jpg')}}" 
                                    @endif  alt="">
                                {{-- </a> --}}
                            </div>
                        </div>
                        <div class="widget-products__info align-self-center">
                            <div class="widget-products__name text-dark">
                                {{$item->name}}
                            </div>
                            <div class="widget-products__prices">
                                @if($item->cashback->type=='fixed'){{$item->cashback->currency}} @endif{{$item->cashback->sale_commission}}@if($item->cashback->type=='percentage')%@endif Cashback
                            </div>
                        </div>
                    </a>
           
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>