@extends('layouts.admin-dashboard.app')

@section('content')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h4 class="nk-block-title page-title">Networks</h4>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                @include('flash::message')
                <div class="nk-block">
                    <div class="row g-gs">
                        @foreach ($networks as $network)
                        <div class="col-xxl-6">
                            <div class="nk-download">
                                <div class="data">
                                    <div class="thumb"><img src="{{asset('admin-dashboard/images/'.$network->logo)}}" alt=""></div>
                                    <div class="info">
                                        <h6 class="title"><span class="name">{{$network->name}}</span></h6>
                                        <div class="meta">
                                            <span class="version">
                                                <span class="text-soft">Stores: </span> <span>{{count($network->stores)}}</span>
                                            </span>
                                            <span class="release">
                                                <span class="text-soft">Click Parameter: </span> <span>{{$network->click_ref}}</span>
                                            </span>
                                            <span class="release">
                                                @if($network->importerSetting)
                                                    <span class="text-soft">Last Imported: </span>
                                                    <span>
                                                        @if($network->importerSetting->last_import_at)
                                                            @php echo \Carbon\Carbon::createFromTimeStamp(strtotime($network->importerSetting->last_import_at))->diffForHumans() @endphp
                                                        @else
                                                            Never
                                                        @endif
                                                    </span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="actions">
                                    <span class="tooltip-importer" @if(count($queue)) data-toggle='tooltip' data-placement='top' title='Importer is running on background please wait' @endif>
                                        <button href="{{ route('admin.importer.importer_setting_form', $network->id) }}" class="btn btn-success importer_btn" @if(count($queue)) style="pointer-events:none;opacity: .6;" @endif>
                                            <em class="icon ni ni-download"></em>
                                            <span>Importer</span>
                                        </button>
                                    </span>

                                    <div class="drodown d-inline">
                                        <a href="#" class="dropdown-toggle btn  btn-primary" data-toggle="dropdown"><em class="icon ni ni-plus mr-1"></em>Options</a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <ul class="link-list-opt no-bdr d-block">
                                                <a href="{{route('admin.networks.edit',$network)}}" data-toggle="modal" data-target="#modalsettings{{$network->name}}">
                                                    <em class="icon ni ni-setting"></em>
                                                    <span>Settings</span>
                                                </a>

                                                <a href="{{route('admin.networks.categories',$network)}}">
                                                    <em class="icon ni ni-eye"></em>
                                                    <span>Categories</span>
                                                </a>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .sp-pdl-item -->
                        </div><!-- .col -->

                        <!-- CJ Settings Modal -->
                        <div class="modal fade" tabindex="-1" id="modalsettingsCJ">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                                    <div class="modal-body">
                                        <div class="nk-modal">
                                            <h4 class="nk-modal-title">{{$network->name}} Settings</h4>
                                            <div class="nk-modal-text">
                                                <form class="network_form_settings" action="{{route('admin.settings.settings_save')}}" method="post">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label class="form-label" for="cj_website_id">Website ID</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" name="cj_website_id" id="cj_website_id" value="{{$settings['cj_website_id']}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label" for="cj_requestor_id">Requestor ID/ CID</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" name="cj_requestor_id" id="cj_requestor_id" value="{{$settings['cj_requestor_id']}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label" for="cj_authorization_token">Authorization Token</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" name="cj_authorization_token" id="cj_authorization_token" value="{{$settings['cj_authorization_token']}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="nk-modal-action">
                                                        <button type="submit" class="btn btn-mw btn-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="nk-modal-action">
                                                <p class="setting-message"></p>
                                            </div>
                                        </div>
                                    </div><!-- .modal-body -->
                                </div>
                            </div>
                        </div>

                        <!-- Webgains Settings Modal -->
                        <div class="modal fade" tabindex="-1" id="modalsettingsWebgains">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                                    <div class="modal-body">
                                        <div class="nk-modal">
                                            <h4 class="nk-modal-title">Webgains Settings</h4>
                                            <div class="nk-modal-text">
                                                <form class="network_form_settings" action="{{route('admin.settings.settings_save')}}" method="post">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label class="form-label" for="webgains_campaignid">Campaign ID</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" name="webgains_campaignid" id="webgains_campaignid" value="{{$settings['webgains_campaignid']}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label" for="webgains_api_key">Api Key</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" name="webgains_api_key" id="webgains_api_key" value="{{$settings['webgains_api_key']}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label" for="webgains_user_name">Username</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" name="webgains_user_name" id="webgains_user_name" value="{{$settings['webgains_user_name']}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label" for="webgains_password">Password</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" name="webgains_password" id="webgains_password" value="{{$settings['webgains_password']}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="nk-modal-action">
                                                        <button type="submit" class="btn btn-mw btn-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="nk-modal-action">
                                                <p class="setting-message"></p>
                                            </div>
                                        </div>
                                    </div><!-- .modal-body -->
                                </div>
                            </div>
                        </div>

                        <!-- Awin Settings Modal -->
                        <div class="modal fade" tabindex="-1" id="modalsettingsAwin">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                                    <div class="modal-body">
                                        <div class="nk-modal">
                                            <h4 class="nk-modal-title">Awin Settings</h4>
                                            <div class="nk-modal-text">
                                                <form class="network_form_settings" action="{{ route('admin.settings.settings_save') }}" method="post">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label class="form-label" for="awin_publisher_id">Publisher ID</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" name="awin_publisher_id" id="awin_publisher_id" value="{{ $settings['awin_publisher_id'] }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label" for="awin_authorization_token">Authorization Token</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" name="awin_authorization_token" id="awin_authorization_token" value="{{ $settings['awin_authorization_token'] }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="nk-modal-action">
                                                        <button type="submit" class="btn btn-mw btn-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="nk-modal-action">
                                                <p class="setting-message"></p>
                                            </div>
                                        </div>
                                    </div><!-- .modal-body -->
                                </div>
                            </div>
                        </div>

                        @endforeach




                    </div><!-- .row -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Alert -->
<div class="modal fade run-model" tabindex="-1" id="modalAlert">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
            <div class="modal-body modal-body-lg text-center">
                <div class="nk-modal">
                    <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-download bg-success"></em>
                    <h4 class="nk-modal-title">Run Importer</h4>
                    <div class="nk-modal-text" id="setting_form"></div>
                    <div class="nk-modal-action">
                        <a href="{{ route('admin.importer.import') }}" class="btn btn-sm btn-mw btn-primary run-importer">Run Importer</a>
                        <a href="" class="btn btn-sm btn-mw btn-primary save_importer">Save for later</a>
                    </div>
                    <div class="nk-modal-action">
                        <p class="setting-message"></p>
                    </div>
                </div>
            </div><!-- .modal-body -->
            <div class="modal-footer bg-lighter">
                <div class="text-center w-100">
                    <p>Import stores, categories, cashbacks and vouchers</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alert -->
<div class="modal fade success-model" tabindex="-1" id="modalAlerts">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
            <div class="modal-body modal-body-lg text-center">
                <div class="nk-modal">
                    <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-check bg-success"></em>
                    <h4 class="nk-modal-title">Importing data</h4>
                    <div class="nk-modal-text">
                        <div class="caption-text">Importer is running in background and it will import all the data,</div>
                        <span class="sub-text-sm">You can still use the application while the importer is running in background</a></span>
                    </div>
                    <div class="nk-modal-action">
                        <a href="#" class="btn btn-lg btn-mw btn-primary" data-dismiss="modal">OK</a>
                    </div>

                </div>
            </div><!-- .modal-body -->
            <div class="modal-footer bg-lighter">
                <div class="text-center w-100">
                    <p></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (min-width: 768px) {
        .nk-download .thumb {
            width: 4.5rem !important;
            margin-right: .8rem !important;
        }
    }
</style>


@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $(document).on('click', '.run-importer', function(event) {
            event.preventDefault();

            var formElement = document.querySelector("#settings_form");

            $.ajax({
                url: '{{ route("admin.importer.import") }}',
                method: "POST",
                data: new FormData(formElement),
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                success: function(data) {
                    $(".run-model").modal('hide');
                    $(".success-model").modal('show');
                    $(".importer_btn").css('pointer-events', 'none')
                    $(".importer_btn").css('opacity', '.6')
                    $(".tooltip-importer").attr('data-toggle', 'tooltip')
                    $(".tooltip-importer").attr('data-placement', 'top')
                    $(".tooltip-importer").attr('title', 'Importer is fetching data please wait')
                    $(".tooltip-importer").attr('data-original-title', 'Importer is fetching data please wait')
                }
            });

        });
    });
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.save_importer', function(event) {
            event.preventDefault();

            var formElement = document.querySelector("#settings_form");
            fd = new FormData(formElement);

            $.ajax({
                url: '{{ route("admin.importer.save_settings") }}',
                method: "POST",
                data: fd,
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                success: function(data) {
                    $(".setting-message").text(data);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $(document).on('submit', '.network_form_settings', function(event) {
            event.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {

                    (function(NioApp, $) {
                        'use strict';
                        toastr.clear();
                        NioApp.Toast(data.message, data.response);
                    })(NioApp, jQuery);
                    location.reload(true);

                },
                error: function(data) {

                    (function(NioApp, $) {
                        'use strict';
                        toastr.clear();
                        NioApp.Toast(data.message, data.response);
                    })(NioApp, jQuery);

                }
            });

        });
    });
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.importer_btn', function(event) {
            event.preventDefault();

            $.ajax({
                url: $(this).attr('href'),
                method: "GET",
                data: {},
                success: function(data) {
                    $('#modalAlert').modal('show');
                    $('#setting_form').html(data);
                }
            });
        });
    });
</script>

@endpush