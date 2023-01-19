<style>
    .block-banner__title__bg {
        background: white;
    }
</style>
<!-- .block-banner -->
<div class="block block-banner mt-4">
    <a href="" class="block-banner__body">
        <div class="block-banner__image block-banner__image--desktop" @if($page->banner_image != NULL && $page->banner_image != '')
            @if(!file_exists(storage_path('app/public/categories/images/' . $page->banner_image)))
                    style="background-image: url({{ asset('frontend/images/banners/categories/cashback.png') }})"
                @else
                    style="background-image: url({{ asset('storage/categories/images/' . $page->banner_image) }})"
                @endif
            @else
                style="background-image: url({{ asset('frontend/images/banners/categories/cashback.png') }})"
            @endif ></div>
        <div class="block-banner__image block-banner__image--mobile" @if($page->banner_image != NULL && $page->banner_image != '')
            @if(!file_exists(storage_path('app/public/categories/images/' . $page->banner_image)))
                    style="background-image: url({{ asset('frontend/images/banners/categories/cashback.png') }})"
                @else
                    style="background-image: url({{ asset('storage/categories/images/' . $page->banner_image) }})"
                @endif
            @else
                style="background-image: url({{ asset('frontend/images/banners/categories/cashback.png') }})"
            @endif ></div>
        <div class="block-banner__title d-flex justify-content-center">
            <div class="col-lg-6">
                <div class="block-banner__title__bg">{{ $page->title }}</div>
            </div>
        </div>
    </a>
</div>
<!-- .block-banner / end -->
