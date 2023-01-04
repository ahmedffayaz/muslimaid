<div class="quickview">
    <button class="quickview__close" type="button">
        <svg width="20px" height="20px">
            <use xlink:href="/frontend/images/sprite.svg#cross-20"></use>
        </svg>
    </button>
    <div class="product product--layout--quickview" data-layout="quickview">
        <div class="product__content">
            <!-- .product__gallery -->
            <div class="product__gallery">
                <div class="product-gallery">
                    <div class="">
                        <div class="owl-carousel" id="product-image" style="display: block !important">
                            <div class="product-image product-image--location--gallery">
                            </div>
                            <div class="product-image product-image--location--gallery">
                            
                                <a href="{{asset('storage/charities/images/'.$charity->banner_upload)}}" data-width="700" data-height="700" class="product-image__body" target="_blank">
                                    <img class="product-image__img" src="{{asset('storage/charities/images/'.$charity->banner_upload)}}" alt="no img">
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <!-- .product__gallery / end -->
            <!-- .product__info -->
            <div class="product__info">
                <div class="product__wishlist-compare">
                    <button type="button" class="btn btn-sm btn-light btn-svg-icon" data-toggle="tooltip" data-placement="right" title="Wishlist">
                        <svg width="16px" height="16px">
                            <use xlink:href="images/sprite.svg#wishlist-16"></use>
                        </svg>
                    </button>
                    <button type="button" class="btn btn-sm btn-light btn-svg-icon" data-toggle="tooltip" data-placement="right" title="Compare">
                        <svg width="16px" height="16px">
                            <use xlink:href="images/sprite.svg#compare-16"></use>
                        </svg>
                    </button>
                </div>
                
                <h1 class="product__name">{{$charity->title}}</h1>
                <ul class="product__meta">
                    <li class="product__meta-availability">Charity Type: <span class="text-success">{{$charity->charity_type->title}}</span></li>
                    <li>Country: <a href="">{{$charity->country}}</a></li>
                    <li> {!! $charity->status ==1  ? '<span class="tb-status badge badge-success">Active</span>' : '<span class="tb-status badge badge-warning">In-active</span>'!!}
                    </li>
                </ul>
            </div>
            <!-- .product__info / end -->
            <!-- .product__sidebar -->
            <div class="product__sidebar">
                <div class="product__availability">
                    Country: <span class="text-success">Pakistan</span>
                </div>
                <!-- .product__options -->
                <form class="product__options">
                    <div class="product__description pt-3">
                        {!!$charity->description!!}
                    </div>
                </form>
                <!-- .product__options / end -->
            </div>
            <!-- .product__end -->
        </div>
    </div>
</div>