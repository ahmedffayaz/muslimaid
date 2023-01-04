<!-- .block-banner -->
<div class="block block-banner">
    <div class="container mt-4">
        <a href="" class="block-banner__body">
            <div class="block-banner__image block-banner__image--desktop" @if($mainCategory->banner_type == 'upload' && $mainCategory->banner_upload != NULL && $mainCategory->banner_upload != '')
                @if(!file_exists(storage_path('app/public/categories/images/' . $mainCategory->banner_upload)))
                        style="background-image: url({{ asset('frontend/images/banners/categories/cashback-bg-image.jpg') }})"
                    @else
                        style="background-image: url({{ asset('storage/categories/images/' . $mainCategory->banner_upload) }})"
                    @endif
                @else
                    style="background-image: url({{ asset('frontend/images/banners/categories/cashback-bg-image.jpg') }})"
                @endif ></div>
            <div class="block-banner__image block-banner__image--mobile" @if($mainCategory->banner_type == 'upload' && $mainCategory->banner_upload != NULL && $mainCategory->banner_upload != '')
                @if(!file_exists(storage_path('app/public/categories/images/' . $mainCategory->banner_upload)))
                        style="background-image: url({{ asset('frontend/images/banners/categories/cashback-bg-image.jpg') }})"
                    @else
                        style="background-image: url({{ asset('storage/categories/images/' . $mainCategory->banner_upload) }})"
                    @endif
                @else
                    style="background-image: url({{ asset('frontend/images/banners/categories/cashback-bg-image.jpg') }})"
                @endif ></div>
            <div class="block-banner__title" style="color: black;">{{ $mainCategory->name }} <br class="block-banner__mobile-br"> Hand Tools</div>
        </a>
    </div>
</div>
<!-- .block-banner / end -->
