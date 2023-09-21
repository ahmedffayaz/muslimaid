<div class="card-inner px-0 table-responsive">
    <div class="nk-tb-list nk-tb-ulist">
        @if (count($appeals))
            <div class="nk-tb-item nk-tb-head">


                <div class="nk-tb-col "><span class="sub-text">Title</span></div>
                <div class="nk-tb-col "><span class="sub-text">Description</span></div>
                <div class="nk-tb-col text-center"><span class="sub-text">Status</span></div>
                <div class="nk-tb-col nk-tb-col-tools text-right">
                    <span class="sub-text">Action</span>

                </div>
            </div><!-- .nk-tb-item -->
            @foreach ($appeals as $appeal)
                <div class="nk-tb-item">
                    <div class="nk-tb-col">
                        <div class="tb-lead"><span><a href="{{route(getAdminPrefix() . '.appeals.edit',$appeal)}}" class="a_link">{{ $appeal->title }}</a></span></div>
                    </div>
                    <div class="nk-tb-col">
                        <span>{{ substr(strip_tags($appeal->description), 0, 50) }}..</span>
                    </div>
                    <div class="nk-tb-col text-center">
                        {!! $appeal->status == 1 ? '<span class="tb-status badge badge-success">Active</span>' : '<span class="tb-status badge badge-warning">In-active</span>' !!}

                    </div>
                    <div class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li>
                                <div class="drodown">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="{{ route(getAdminPrefix() . '.appeals.edit', $appeal) }}"><em class="icon ni ni-edit"></em><span>Edit Appeal</span></a></li>
                                            <li><a class='delete' form_id="delete-{{ $appeal->id }}" style="cursor: pointer"> <em class="icon ni ni-trash-fill"></em><span>Delete Appeal</span></a>

                                                <form action="{{ route(getAdminPrefix() . '.appeals.destroy', $appeal) }}" id="delete-{{ $appeal->id }}" method="POST"
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
        </div>
    </div>
    <div class="nk-block-between-md g-3 card-inner align-right">
        <div class="pagination g">
            {!! $appeals->links() !!}

        </div>
    </div><!-- .nk-block-between -->
@else
    <h3 class="m-auto text-center py-5">No Appeals found</h3>
@endif
