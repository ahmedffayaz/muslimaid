<style>
    .block-banner__title__bg {
        background: white;
        padding: 10px 0px 10px 0px;
    }
</style>

<div class="block block-banner mt-4">
    <a href="" class="block-banner__body">
        <div class="block-banner__image block-banner__image--desktop"
            @if ($mainCategory->banner_type == 'upload' && $mainCategory->banner_upload != null && $mainCategory->banner_upload != '') 
                @if (!file_exists(storage_path('app/public/categories/images/' . $mainCategory->banner_upload)))
                    style="background-image: url({{ asset('frontend/images/banners/categories/cashback.png') }})"
                @else
                    style="background-image: url({{ asset('storage/categories/images/' . $mainCategory->banner_upload) }})"
                @endif
            @else
                style="background-image: url({{ asset('frontend/images/banners/categories/cashback.png') }})" 
            @endif></div>
        <div class="block-banner__image block-banner__image--mobile"
            @if ($mainCategory->banner_type == 'upload' && $mainCategory->banner_upload != null && $mainCategory->banner_upload != '') 
                @if (!file_exists(storage_path('app/public/categories/images/' . $mainCategory->banner_upload)))
                    style="background-image: url({{ asset('frontend/images/banners/categories/cashback.png') }})"
                @else
                    style="background-image: url({{ asset('storage/categories/images/' . $mainCategory->banner_upload) }})"
                @endif
            @else
                style="background-image: url({{ asset('frontend/images/banners/categories/cashback.png') }})"
            @endif></div>
        <div class="block-banner__title d-flex justify-content-center">
            <div class="col-lg-6">
                <div class="block-banner__title__bg">{{ $mainCategory->name }}</div>
            </div>
        </div>
    </a>
</div>
