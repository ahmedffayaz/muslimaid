@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Charity Types</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total {{count($CharityType)}} Charity Types.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->

                        <!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                @include('flash::message')
                <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="card-inner-group">

                            <div class="card-inner px-0">
                                <div class="nk-tb-list nk-tb-ulist" id="table-data">

                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span class="sub-text">Name</span></div>
                                        <div class="nk-tb-col"><span class="sub-text">Status</span></div>
                                        <div class="nk-tb-col nk-tb-col-tools text-right">
                                            <span class="sub-text">Action</span>

                                        </div>
                                    </div><!-- .nk-tb-item -->

                                    @foreach ($CharityType as $charity)
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col">
                                            <div class="tb-lead"><span><a class="edit-charity"
                                                        href="{{ route('admin.charities.charity_type_edit', $charity) }}"
                                                        class="a_link">{{ $charity->title }}</a></span></div>
                                        </div>
                                        <div class="nk-tb-col">
                                            {!! $charity->status == 1 ? '<span
                                                class="tb-status badge badge-success">Active</span>' : '<span
                                                class="tb-status badge badge-warning">In-active</span>' !!}

                                        </div>
                                        <div class="nk-tb-col nk-tb-col-tools">
                                            <ul class="nk-tb-actions gx-1">
                                                <li>
                                                    <div class="drodown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                            data-toggle="dropdown"><em
                                                                class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <ul class="link-list-opt no-bdr">
                                                                <li><a class="edit-charity"
                                                                        href="{{ route('admin.charities.charity_type_edit', $charity) }}"><em
                                                                            class="icon ni ni-edit"></em><span>Edit
                                                                        </span></a></li>
                                                                <li><a class='delete'
                                                                        form_id="delete-{{ $charity->id }}"
                                                                        style="cursor: pointer"> <em
                                                                            class="icon ni ni-trash-fill"></em><span>Delete</span></a>
                                                                    <form
                                                                        action="{{ route('admin.charity_type_delete', $charity->id) }}"
                                                                        id="delete-{{ $charity->id }}" method=""
                                                                        class="m-0">
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

                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
                <!-- Edit Charity Modal -->
                <div class="modal fade" tabindex="-1" id="charity-modal">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">

                            <div class="modal-header align-center">
                                <div class="nk-file-title">

                                    <div class="nk-file-name">
                                        <div class="nk-file-name-text"><span class="title">Edit Charity Type</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                            </div>
                            <div id="voucher-form" class=" p-4">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nk-block-between-md g-3 card-inner">
                    <div class="pagination g">
                        {!! $CharityType->links()!!}
                    </div>
                </div><!-- .nk-block-between -->
            </div>
        </div>
    </div>
</div>


@endsection
@push('scripts')
<script>
    $(document).ready(function() {
            $(document).on('click', '.delete', function(event) {
                var form_id = $(this).attr('form_id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then(function(result) {
                    if (result.value) {
                        $('#' + form_id).submit();
                    }
                });
                event.preventDefault();
            });
        });
</script>
<script>
    $(document).on('click', '.edit-charity', function(event) {
            event.preventDefault();
            var pageurl = $(this).attr('href');
            var _token = $("input[name=_token]").val();
            $.ajax({

                url: pageurl,
                method: "GET",
                data: {
                    _token: _token
                },
                success: function(data) {
                    $('#charity-modal').modal('show');
                    $('#voucher-form').html(data);
                    $('.form-validate').each(function() {
                        validateData($(this));
                    });
                }
            });
        });
</script>
@endpush