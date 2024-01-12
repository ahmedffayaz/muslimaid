@include('flash::message')
<div class="card-inner px-0 table-responsive">
    <div class="nk-tb-list nk-tb-ulist">
        @if (count($coms))
            <div class="nk-tb-item nk-tb-head">
                <div class="nk-tb-col"><span class="sub-text">User</span></div>
                <div class="nk-tb-col"><span class="sub-text">Store</span></div>
                <div class="nk-tb-col"><span class="sub-text">Network Commission
                        ({{ getCurrencySymbol() }})</span></div>
                @if (getImporterYMLSettings(config('app.cashback_earnings_admin_yml_path')))
                    <div class="nk-tb-col"><span class="sub-text">Cashback ({{ getCurrencySymbol() }})</span></div>
                    <div class="nk-tb-col"><span class="sub-text">Exit Click Id</span></div>
                @endif
                <div class="nk-tb-col"><span class="sub-text">Event Time</span></div>
                <div class="nk-tb-col"><span class="sub-text">Status</span></div>
                <div class="nk-tb-col nk-tb-col-tools text-right"><span class="sub-text" style="min-width: 40px">Action</span></div>
            </div><!-- .nk-tb-item -->
            @foreach ($coms as $commission)
                <div class="nk-tb-item">
                    <div class="nk-tb-col">
                        <div class="user-card">
                            <div class="user-avatar {{ getRandomColorClass() }}">
                                <span>
                                    {{ isset($commission->exitClick) && isset($commission->exitClick->user) ? ($commission->user->first_name[0] ?? 'N') . ($commission->user->last_name[0] ?? 'A') : 'NA' }}</span>
                            </div>
                            <div class="user-info">

                                @if ($commission->store_id && isset($commission->exitClick->user) && isset($commission->user))
                                    <span class="tb-lead">{{ $commission->exitClick->user->id ?? '' }} @if ($commission->user->first_name != 'unnamed' || $commission->user->last_name != 'unnamed')
                                            - {{ $commission->user->first_name }} {{ $commission->user->last_name }}
                                        @endif
                                    </span>
                                    <span>{{ $commission->user->email ?? '' }}</span>
                                @elseif(isset($commission->user))
                                    <span class="tb-lead">{{ $commission->user->id }} @if ($commission->user->first_name != 'unnamed' || $commission->user->last_name != 'unnamed')
                                            - {{ $commission->user->first_name }} {{ $commission->user->last_name }}
                                        @endif
                                    </span>
                                    <span>{{ $commission->user->email ?? '' }}</span>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="nk-tb-col">
                        @if ($commission->store_id)
                            <span><b>{{ $commission->store->id ?? '' }} - {{ $commission->store->name ?? '' }}</b></span>
                        @else
                            <span> {{ ucfirst(str_replace('_', ' ', $commission->type)) }}</span>
                        @endif
                    </div>

                    <div class="nk-tb-col">
                        <span>
                            <span class="currency">{{ currency($commission->network_commission) }}</span></span>
                    </div>
                    @if (getImporterYMLSettings(config('app.cashback_earnings_admin_yml_path')))
                        <div class="nk-tb-col">
                            <span>
                                <span class="currency">{{ currency($commission->amount) }}</span></span>
                        </div>
                        <div class="nk-tb-col">
                            @if ($commission->store_id)
                                <span>{{ $commission->exit_click_id }}</span>
                            @else
                                <span>-</span>
                            @endif
                        </div>
                    @endif
                    <div class="nk-tb-col">
                        @if ($commission->store_id)
                            <span>{{ $commission->event_date }}</span>
                        @else
                            <span>{{ formatDateTimezone($commission->created_at, 'YYYY-MM-DD HH:mm:ss') }}</span>
                        @endif
                    </div>

                    <div class="nk-tb-col">
                        @if (($commission->statusMap->status ?? $commission->status) == 'confirmed')
                            <span class="tb-status badge badge-success">{{ $commission->statusMap->status ?? $commission->status }}</span>
                        @elseif(($commission->statusMap->status ?? $commission->status) == 'paid')
                            <span class="tb-status badge badge-success">{{ $commission->statusMap->status ?? $commission->status }}</span>
                        @elseif(($commission->statusMap->status ?? $commission->status) == 'processing')
                            <span class="tb-status badge badge-info">{{ $commission->statusMap->status ?? $commission->status }}</span>
                        @elseif(($commission->statusMap->status ?? $commission->status) == 'failed')
                            <span class="tb-status badge badge-danger">{{ $commission->statusMap->status ?? $commission->status }}</span>
                        @elseif(($commission->statusMap->status ?? $commission->status) == 'pending')
                            <span class="tb-status badge badge-info">{{ $commission->statusMap->status ?? $commission->status }}</span>
                        @elseif(($commission->statusMap->status ?? $commission->status) == 'processing donation')
                            <span class="tb-status badge badge-warning">{{ $commission->statusMap->status ?? $commission->status }}</span>
                        @elseif(($commission->statusMap->status ?? $commission->status) == 'donated')
                            <span class="tb-status badge badge-info">{{ $commission->statusMap->status ?? $commission->status }}</span>
                        @endif
                    </div>
                    <div class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li>
                                <div class="drodown">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="{{ route(getAdminPrefix() . '.commissions.edit', $commission) }}" cashback-id='{{ $commission->id }}' class='cashback-edit'><em
                                                        class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                            <li><a href="{{ route(getAdminPrefix() . '.commissions.history', $commission) }}" class='cashback-history'><em class="icon ni ni-eye"></em><span>Status
                                                        History</span></a></li>
                                            <li><a onclick="$('#delete-commission-{{ $commission->id }}').submit();" style="cursor: pointer"> <em
                                                        class="icon ni ni-trash-fill"></em><span>Delete</span></a>
                                                <form action="{{ route(getAdminPrefix() . '.commissions.destroy', $commission) }}" id="delete-commission-{{ $commission->id }}" method="POST"
                                                    class="m-0">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div><!-- .nk-tb-item -->
            @endforeach
        </div><!-- .nk-tb-list -->
    </div><!-- .card-inner -->
    <div class="nk-block-between-md g-3 card-inner float-right">
        <div class="pagination g" route="{{ $route }}">
            {!! $coms->onEachSide(1)->links() !!}
        </div>
    </div><!-- .nk-block-between -->
@else
    <h3 class="m-auto text-center py-5">No results found</h3>
@endif
