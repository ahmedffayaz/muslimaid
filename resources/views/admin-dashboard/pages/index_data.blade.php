<div class="nk-tb-item nk-tb-head">
    <div class="nk-tb-col"><span class="sub-text">Title</span></div>
    <div class="nk-tb-col"><span class="sub-text">Slug</span></div>
    <div class="nk-tb-col"><span class="sub-text">View</span></div>
    <div class="nk-tb-col"><span class="sub-text">Page Type</span></div>
    <div class="nk-tb-col"><span class="sub-text">Status</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
    </div>
</div>

@foreach ($pages as $page)
    <div class="nk-tb-item">
        <div class="nk-tb-col">
            <div class="tb-lead"><span><a href="{{ route(getAdminPrefix() . '.pages.edit', $page) }}" class="a_link">{{ $page->title }}</a></span></div>
        </div>
        <div class="nk-tb-col">
            <span>{{ $page->slug }}</span>
        </div>
        <div class="nk-tb-col">
            <span>
                @if ($page->type == 'system')
                <a href="{{ url($page->slug) }}" target="_blank" class="a_link">
                    <em class="icon ni ni-link-alt"></em>
                </a>
                @else
                <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="a_link">
                    <em class="icon ni ni-link-alt"></em>
                </a>
                @endif
            </span>
        </div>
        <div class="nk-tb-col">
            @if ($page->type == 'system')
                <span class="tb-status badge badge-warning">System</span>
            @elseif ($page->type == 'special')
                <span class="tb-status badge badge-info">Special</span>
            @else
                <span class="tb-status badge badge-success">General</span>
            @endif
        </div>
        <div class="nk-tb-col">
            {!! $page->status == 'active' ? '<span class="tb-status badge badge-success">Active</span>' : '<span class="tb-status badge badge-danger">In-active</span>' !!}
        </div>
        <div class="nk-tb-col nk-tb-col-tools">
            <ul class="nk-tb-actions gx-1">
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="{{ route(getAdminPrefix() . '.pages.edit', $page) }}"><em class="icon ni ni-edit"></em><span>Edit Page</span></a></li>
                                @if ($page->type == 'general' && $page->status == 'inactive')
                                    <li>
                                        <a class='delete' form_id="delete-page-{{ $page->id }}" style="cursor: pointer">
                                            <em class="icon ni ni-trash-fill"></em>
                                            <span>Delete Page</span>
                                        </a>
                                        <form action="{{ route(getAdminPrefix() . '.pages.destroy', $page) }}" id="delete-page-{{ $page->id }}" method="POST" class="m-0">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
@endforeach

<div class="nk-block-between-md g-3 card-inner">
    <div class="pagination g">
        {!! $pages->links() !!}
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.page-link', function(event) {
                window.location.href = $(this).attr('href');
            });
        });
    </script>
@endpush
