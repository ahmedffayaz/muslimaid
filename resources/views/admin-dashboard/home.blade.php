@extends('layouts.admin-dashboard.app')

@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Dashboard</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li>
                                                <div class="drodown">
                                                    <a href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light" data-toggle="dropdown">
                                                        <em class="d-none d-sm-inline icon ni ni-calender-date"></em>

                                                        <span><span class="d-none d-md-inline">Last </span><span id="period"></span></span>

                                                        <em class="dd-indc icon ni ni-chevron-right"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="link-list-opt no-bdr">
                                                            <li><a onclick="fetchData(1)"><span>Last 24 Hours</span></a></li>
                                                            <li><a onclick="fetchData(7)"><span>Last 7 Days</span></a></li>
                                                            <li><a onclick="fetchData(30)"><span>Last 30 Days</span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                            {{-- <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span></a></li> --}}
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block" id="home_data">
                    </div><!-- .nk-block -->
                    <!-- @@ Review Modal @e -->
                    <div class="modal fade" tabindex="-1" role="dialog" id="review-modal">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header align-center">
                                    <div class="nk-file-title">
                                        <div class="nk-file-name">
                                            <div class="nk-file-name-text"><span class="title">Review</span></div>
                                        </div>
                                    </div>
                                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                                </div>
                                <div id="review" class=" p-4">
                                </div>
                            </div><!-- .modal-content -->
                        </div><!-- .modla-dialog -->
                    </div><!-- .modal -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        fetchData(0);

        function fetchData(period) {
            $('#home_data').html(`<div class="spinner-border float-right" style="width: 3rem; height: 3rem;" role="status">
  <span class="sr-only">Loading...</span>
</div>`);
            pageurl = "{{ route(getAdminPrefix() . '.home.index_data') }}"
            var _token = $("input[name=_token]").val();
            $.ajax({

                url: pageurl,
                method: "POST",
                data: {
                    _token: _token,
                    period: period
                },
                success: function(data) {
                    $('#home_data').html(data);

                    var updatedperiod = $('.period').text();
                    if (updatedperiod == 1) {
                        $('#period').text('24 hours');
                    } else {
                        $('#period').text(updatedperiod + ' days');
                    }

                }
            });
        }
        $(document).ready(function() {
            $(document).on('click', '.review-edit', function(event) {
                event.preventDefault();
                var id = $(this).attr('review-id');
                pageurl = $(this).attr('href');
                var _token = $("input[name=_token]").val();
                $.ajax({
                    url: pageurl,
                    method: "GET",
                    data: {
                        _token: _token
                    },
                    success: function(data) {
                        $('#review-modal').modal('show');
                        $('#review').html(data);
                    }
                });

            });
        });
    </script>
@endpush
