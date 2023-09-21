<div class="card-inner px-0 table-responsive">
    <div class="nk-tb-list nk-tb-ulist">
        @if (count($testimonials))
            <div class="nk-tb-item nk-tb-head">
                <div class="nk-tb-col"><span class="sub-text">Title</span></div>
                <div class="nk-tb-col"><span class="sub-text">User name</span></div>
                <div class="nk-tb-col"><span class="sub-text">Description</span></div>
                <div class="nk-tb-col"><span class="sub-text">Status</span></div>
                <div class="nk-tb-col nk-tb-col-tools text-right">
                    <span class="sub-text">Action</span>
                </div>
            </div>
            @foreach ($testimonials as $testimonial)
                <div class="nk-tb-item">
                    <div class="nk-tb-col">
                        <div class="tb-lead"><span><a href="{{ route(getAdminPrefix() . '.testimonials.edit', $testimonial) }}" class="a_link">{{ $testimonial->title }}</a></span></div>
                    </div>
                    <div class="nk-tb-col">
                        <span>{{ $testimonial->name }}</span>
                    </div>
                    <div class="nk-tb-col">
                        <span>{{ $testimonial->description }}</span>
                    </div>
                    <div class="nk-tb-col">
                        <span>{{ $testimonial->status }}</span>
                    </div>
                    <div class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li>
                                <div class="drodown">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="{{ route(getAdminPrefix() . '.testimonials.edit', $testimonial) }}"><em class="icon ni ni-edit"></em><span>Edit Testimonial</span></a></li>
                                            <li><a form_id="delete-blog-{{ $testimonial->id }}" class="delete" style="cursor: pointer"> <em
                                                        class="icon ni ni-trash-fill"></em><span>Delete Testimonial</span></a>

                                                <form action="{{ route(getAdminPrefix() . '.testimonials.destroy', $testimonial) }}" id="delete-blog-{{ $testimonial->id }}" method="POST"
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
    <div class="nk-block-between-md g-3 card-inner float-right">
        <div class="pagination g">
            {!! $testimonials->links() !!}
        </div>
    </div>
@else
    <h3 class="m-auto text-center py-5">No record found</h3>
@endif
@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.page-link', function(event) {
                window.location.href = $(this).attr('href');
            });
        });
    </script>
@endpush
