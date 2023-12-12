<div class="card-inner px-0 table-responsive">
    <div class="nk-tb-list nk-tb-ulist">
        @if (count($banners))
            <div class="nk-tb-item nk-tb-head">


                <div class="nk-tb-col"><span class="sub-text">Title</span></div>
                <div class="nk-tb-col text-center"><span class="sub-text">Type</span></div>
                <div class="nk-tb-col text-center"><span class="sub-text">Image Type</span></div>
                <div class="nk-tb-col text-center"><span class="sub-text">Status</span></div>
                <div class="nk-tb-col nk-tb-col-tools text-right">
                    <span class="sub-text">Action</span>

                </div>
            </div><!-- .nk-tb-item -->
            @foreach ($banners as $banner)
                <div class="nk-tb-item">
                    <div class="nk-tb-col">
                        <div class="tb-lead"><span><a href="{{ route(getAdminPrefix() . '.banners.edit', $banner) }}" class="a_link">{{ $banner->name }}</a></span></div>
                    </div>
                    <div class="nk-tb-col text-center">
                        <span class="tb-status badge badge-primary">{{ ucfirst(str_replace('_', ' ', $banner->type)) }}</span>
                    </div>
                    <div class="nk-tb-col text-center">
                        <span class="tb-status badge badge-info">{{ ucfirst($banner->banner_type) }}</span>
                    </div>
                    <div class="nk-tb-col text-center">
                        {!! $banner->status == 'active' ? '<span class="tb-status badge badge-success">Active</span>' : '<span class="tb-status badge badge-warning">In-active</span>' !!}
                    </div>
                    <div class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li>
                                <div class="drodown">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            @if (getImporterYMLSettings(config('app.banners_yaml_path')))
                                                @can('edit banners')
                                                    <li>
                                                        <a href="{{ route(getAdminPrefix() . '.banners.edit', $banner) }}" class='banner-edit'>
                                                            <em class="icon ni ni-edit"></em><span>Edit Banner</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            @endif
                                            @if (getImporterYMLSettings(config('app.banners_yaml_path')))
                                                @can('delete banners')
                                                    <li>
                                                        <a class='banner-delete' data-id="{{ $banner->id }}" data-url="{{ route(getAdminPrefix() . '.banners.destroy', $banner) }}"
                                                            style="cursor: pointer">
                                                            <em class="icon ni ni-trash-fill"></em><span>Delete Banner</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div><!-- .nk-tb-item -->
            @endforeach
    </div>
</div>
<div class="nk-block-between-md g-3 card-inner float-right">
    <div class="pagination g">
        {!! $banners->links() !!}

    </div>
</div><!-- .nk-block-between -->
@else
<h3 class="m-auto text-center py-5">No Appeals found</h3>
@endif
