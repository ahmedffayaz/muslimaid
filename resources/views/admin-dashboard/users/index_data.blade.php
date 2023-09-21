<div class="card-inner px-0 table-responsive">
    <div class="nk-tb-list nk-tb-ulist">
        @if (count($users))
            <div class="nk-tb-item nk-tb-head">
                <div class="nk-tb-col" style="width: 30%"><span class="sub-text">User</span></div>
                <div class="nk-tb-col tb-col-lg"><span class="sub-text">Balance</span></div>
                <div class="nk-tb-col tb-col-lg"><span class="sub-text">Reg Type</span></div>
                <div class="nk-tb-col tb-col-lg"><span class="sub-text">Reg date</span></div>
                <div class="nk-tb-col tb-col-lg"><span class="sub-text">Status</span></div>
                <div class="nk-tb-col nk-tb-col-tools text-right">
                    <span class="sub-text">Action</span>
                </div>
            </div><!-- .nk-tb-item -->
            @foreach ($users as $user)
                <div class="nk-tb-item">

                    <div class="nk-tb-col" style="width: 30%">
                        <a href="{{ route(getAdminPrefix() . '.users.show_user') }}?user_id={{ $user->id }}">
                            <div class="user-card">
                                <div class="user-avatar {{ getRandomColorClass() }}">
                                    <span>
                                        @if ($user->first_name == 'unnamed' || $user->last_name == 'unnamed')
                                            NA @else{{ $user->first_name[0] }}{{ $user->last_name[0] }}
                                        @endif
                                    </span>
                                </div>
                                <div class="user-info">
                                    <span class="tb-lead">{{ $user->id }} @if ($user->first_name != 'unnamed' || $user->last_name != 'unnamed')
                                            - {{ $user->first_name }} {{ $user->last_name }}
                                        @endif
                                    </span>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="nk-tb-col tb-col-lg">
                        <span class="tb-amount">{{ currency($user->balance->sum('amount')) }}</span>
                    </div>
                    <div class="nk-tb-col tb-col-lg">
                        <span>{{ $user->registration_type }}</span>
                    </div>

                    <div class="nk-tb-col tb-col-lg">
                        <span>{{ formatDateTimezone($user->created_at, 'YYYY-MM-DD HH:mm:ss') }}</span>
                    </div>

                    <div class="nk-tb-col tb-col-lg">
                        @if ($user->status == 'pending')
                            <span class="tb-status badge badge-warning">Pending</span>
                        @elseif ($user->status == 'active')
                            <span class="tb-status badge badge-success">Active</span>
                        @elseif ($user->status == 'in_active')
                            <span class="tb-status badge badge-danger">In-active</span>
                        @endif
                    </div>
                    <div class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li>
                                <div class="drodown">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="{{ route(getAdminPrefix() . '.users.show_user') }}?user_id={{ $user->id }}"><em class="icon ni ni-edit"></em><span>Edit
                                                        user</span></a></li>
                                            <li>
                                                <form action="{{ route(getAdminPrefix() . '.users.destroy', $user) }}" id="delete-form-{{ $user->id }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>

                                                <a class='delete' form_id="delete-form-{{ $user->id }}" style="cursor: pointer">
                                                    <em class="icon ni ni-trash-fill"></em></em><span>Delete User</span>
                                                </a>
                                            </li>
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
    <div class="nk-block-between-md g-3 card-inner align-right">
        <div class="pagination g" route="{{ $route }}">
            {!! $users->onEachSide(1)->links() !!}
        </div>
    </div><!-- .nk-block-between -->
@else
    <h3 class="m-auto text-center py-5">No results found</h3>
@endif
