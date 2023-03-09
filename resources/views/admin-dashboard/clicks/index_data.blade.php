<style>
    .nk-tb-list {
        table-layout: fixed;
    }
</style>
@if (count($clicks))
    <div class="nk-tb-item nk-tb-head">
        <div class="nk-tb-col " style="width: 10%"><span class="sub-text">Click ID</span></div>
        <div class="nk-tb-col "><span class="sub-text">User</span></div>
        <div class="nk-tb-col text-center"><span class="sub-text">Network</span></div>
        <div class="nk-tb-col text-center"><span class="sub-text">Store</span></div>
        <div class="nk-tb-col text-right"><span class="sub-text">Exit Url</span></div>
        <div class="nk-tb-col  text-right"><span class="sub-text">Time</span></div>
        <div class="nk-tb-col  text-right"><span class="sub-text">Conversion</span></div>
    </div><!-- .nk-tb-item -->
    @foreach ($clicks as $click)
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
            <div class="nk-tb-col text-center">
                <span class="tb-status badge badge-primary"><b>{{$click->network->name}}</b></span>
            </div>
            <div class="nk-tb-col text-center">
                <span><b>{{ $click->store->id }} - {{ $click->store->name ?? '' }}</b></span>
            </div>
            <div class="nk-tb-col text-right">
                <h5><a href='{{ $click->exit_url ?? '#' }}' target="_blank" class="a_link"><em class="icon ni ni-link-alt"></em></a></h5>
                <span></span>
            </div>
            <div class="nk-tb-col text-right">
                <span>{{ $click->created_at }}</span>
            </div>
            <div class="nk-tb-col text-right">
                {!! $click->cashback ? '<span class="tb-status badge badge-success">converted</span>' : '<span class="tb-status badge badge-danger">Not converted</span>' !!}
            </div>
        </div><!-- .nk-tb-item -->
    @endforeach
    <div class="nk-block-between-md g-3 card-inner">
        <div class="pagination g" route="{{ $route }}">
            {!! $clicks->links() !!}
        </div>
    </div><!-- .nk-block-between -->
@else
    <h3 class="m-auto text-center py-5">No results found</h3>
@endif
