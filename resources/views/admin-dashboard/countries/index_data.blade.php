@if (count($countries))
    <div class="nk-tb-item nk-tb-head">

        <div class="nk-tb-col"><span class="sub-text">Name</span></div>
        <div class="nk-tb-col"><span class="sub-text">ISO Code</span></div>
        <div class="nk-tb-col"><span class="sub-text">Region</span></div>
        <div class="nk-tb-col"><span class="sub-text">Status</span></div>
        <div class="nk-tb-col nk-tb-col-tools text-right">
            <span class="sub-text">Action</span>

        </div>
    </div><!-- .nk-tb-item -->

    @foreach ($countries as $country)
        <div class="nk-tb-item">
            <div class="nk-tb-col">
                <div class="user-card">
                    <div class="user-info">
                        <span class="tb-lead">{{ $country->name }}</span>
                    </div>
                </div>
            </div>
            <div class="nk-tb-col">
                <span>{{ $country->iso_code }}</span>
            </div>
            <div class="nk-tb-col">
                <span>{{ $country->region->name }}</span>
            </div>
            <div class="nk-tb-col">
                @if ($country->status != 0)
                    <span class="tb-status badge badge-success">Active</span>
                @else
                    <span class="tb-status badge badge-warning">Inactive</span>
                @endif
            </div>
            <div class="nk-tb-col nk-tb-col-tools">
                <ul class="nk-tb-actions gx-1">
                    <li>
                        <div class="drodown">
                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <ul class="link-list-opt no-bdr">
                                    <li><a href="javascript:void(0)" class="edit-form" data-id="{{ $country->id }}"><em class="icon ni ni-edit"></em><span>Edit
                                                Country</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    @endforeach
@else
    <h3 class="m-auto text-center py-5">No Countries found</h3>
@endif
