<div class="nk-tb-list nk-tb-ulist mt-3" style="table-layout: auto">
    @if (count($metaDatas))
        <div class="nk-tb-item nk-tb-head">
            <div class="nk-tb-col pl-0"><span class="sub-text">Type</span></div>
            <div class="nk-tb-col tb-col-mb"><span class="sub-text">Value</span></div>
        </div><!-- .nk-tb-item -->
        @foreach ($metaDatas as $metaData)
            <div class="nk-tb-item">
                <div class="nk-tb-col pl-1">
                    <span>{{ $metaData['type'] ? ucfirst(str_replace('_', ' ', $metaData['type'])) : '' }}</span>
                </div>

                <div class="nk-tb-col">
                    <span><b>{{ $metaData['value'] }}</b></span>
                </div>
            </div><!-- .nk-tb-item -->
        @endforeach
        <div class="nk-block-between-md px-0 card-inner">
            <div class="pagination g" route="" id="meta-data-paginate">
                {!! $metaDatas->links() !!}
            </div>
        </div><!-- .nk-block-between -->
    @else
        <!-- <p>No meta data found</p> -->
    @endif
</div>
