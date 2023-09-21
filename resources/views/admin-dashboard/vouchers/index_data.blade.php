<div class="card-inner px-0 table-responsive">
    <div class="nk-tb-list nk-tb-ulist">
        @if (count($vouchers))

            <div class="nk-tb-item nk-tb-head">
                <div class="nk-tb-col "><span class="sub-text">Store</span></div>
                <div class="nk-tb-col tb-col-mb"><span class="sub-text">Name</span></div>
                <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Coupon</span></div>
                <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Deeplink URL</span></div>
                <div class="nk-tb-col nk-tb-col-tools text-right"><span class="sub-text">Action</span></div>
            </div>

            @foreach ($vouchers as $voucher)
                <div class="nk-tb-item" @if ($voucher->promotion_end_date < \Carbon\Carbon::now()) style="background-color:#f1f1f1" @endif>

                    <div class="nk-tb-col ">
                        <a href="{{ route(getAdminPrefix() . '.stores.show_store') }}?slug={{ optional($voucher->store)->slug }}" class="a_link">
                            <span><b>{{ $voucher->store->id ?? '' }} - {{ $voucher->store->name ?? '' }}</b></span>
                            <br>
                            <span>{{ $voucher->store->network->name ?? '' }}</span>
                        </a>
                        <br>
                        @if ($voucher->promotion_end_date < \Carbon\Carbon::now())
                            <span class="text-danger">Expired</span>
                        @endif
                    </div>

                    <div class="nk-tb-col ">
                        <span>{{ $voucher->name }}</span>
                    </div>

                    <div class="nk-tb-col text-center ">
                        <span>{{ $voucher->coupon_code }}</span>
                    </div>

                    <div class="nk-tb-col text-center">
                        <h5>
                            <a href="{{ $voucher->deeplink_url ?? '#' }}" target="_blank" class="a_link">
                                <em class="icon ni ni-link-alt"></em>
                            </a>
                        </h5>
                    </div>
                    <div class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li>
                                <div class="drodown">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li>
                                                <a class="edit-voucher" href="{{ route(getAdminPrefix() . '.vouchers.edit', $voucher) }}">
                                                    <em class="icon ni ni-edit"></em>
                                                    <span>Edit Voucher</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class='delete' data-action="{{ route(getAdminPrefix() . '.vouchers.destroy', $voucher) }}" form_id="delete-voucher-{{ $voucher->id }}" style="cursor: pointer">
                                                    <em class="icon ni ni-trash-fill"></em>
                                                    <span>Delete Voucher</span>
                                                </a>
                                                <form action="{{ route(getAdminPrefix() . '.vouchers.destroy', $voucher) }}" id="delete-voucher-{{ $voucher->id }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            @endforeach
    </div>
</div>

    <div class="nk-block-between-md g-3 card-inner float-right">
        <div class="pagination g" route="{{ $route }}">
            {!! $vouchers->links() !!}
        </div>
    </div>
@else
    <h3 class="m-auto text-center py-5">No results found</h3>
@endif
