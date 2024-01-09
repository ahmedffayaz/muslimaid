<div class="card-inner px-0 table-responsive">
    <div class="nk-tb-list nk-tb-ulist">
        @if (count($exitClicks))
            <div class="nk-tb-item nk-tb-head">
                <div class="nk-tb-col"><span class="sub-text">Click ID</span></div>
                <div class="nk-tb-col"><span class="sub-text">User</span></div>
                <div class="nk-tb-col"><span class="sub-text">Network</span></div>
                <div class="nk-tb-col"><span class="sub-text">Store</span></div>
                <div class="nk-tb-col"><span class="sub-text">Exit Url</span></div>
                <div class="nk-tb-col"><span class="sub-text">Time</span></div>
                <div class="nk-tb-col"><span class="sub-text">Conversion</span></div>
            </div><!-- .nk-tb-item -->
            @foreach ($exitClicks as $click)
                <div class="nk-tb-item">
                    <div class="nk-tb-col">
                        <span><b>{{ $click->id ?? '' }}</b></span>
                    </div>

                    <div class="nk-tb-col">
                        <div class="user-card">
                            <div class="user-avatar {{ getRandomColorClass() }}">
                                <span>{{ $click->user->first_name[0] ?? 'N' }}{{ $click->user->last_name[0] ?? 'A' }}</span>
                            </div>
                            <div class="user-info">
                                <span class="tb-lead">{{ $click->user->id ?? '' }} @if (@$click->user->first_name != 'unnamed' || @$click->user->last_name != 'unnamed')
                                        - {{ $click->user->first_name ?? '' }} {{ $click->user->last_name ?? '' }}
                                    @endif
                                </span>
                                <span>{{ $click->user->email ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="nk-tb-col">
                        <span class="tb-status badge badge-primary"><b>{{ isset($click->network) ? $click->network->name : '' }}</b></span>
                    </div>
                    <div class="nk-tb-col">
                        <span><b>{{ $click->store()->onlyTrashed()->first()->id }} - {{ $click->store()->onlyTrashed()->first()->name ?? '' }}</b></span>
                    </div>
                    <div class="nk-tb-col">
                        <h5><a href='{{ $click->exit_url ?? '#' }}' target="_blank" class="a_link"><em class="icon ni ni-link-alt"></em></a></h5>
                        <span></span>
                    </div>
                    <div class="nk-tb-col">
                        <span>{{ formatDateTimezone($click->created_at, 'YYYY-MM-DD HH:mm:ss') }}</span>
                    </div>
                    <div class="nk-tb-col">
                        {!! $click->cashback ? '<span class="tb-status badge badge-success">converted</span>' : '<span class="tb-status badge badge-danger">Not converted</span>' !!}
                    </div>
                </div><!-- .nk-tb-item -->
            @endforeach
    </div>
</div>
    <div class="nk-block-between-md g-3 card-inner float-right">
        <div class="pagination g" route="{{ $route }}">
            {!! $exitClicks->links() !!}
        </div>
    </div><!-- .nk-block-between -->
@else
    <h3 class="m-auto text-center py-5">No results found</h3>
@endif