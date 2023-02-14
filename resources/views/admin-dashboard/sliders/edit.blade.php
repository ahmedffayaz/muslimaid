@extends('layouts.admin-dashboard.app')
@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{ $slider->name }} Slides</h3>
                                <div class="nk-block-des text-soft">
                                    {{-- <p>You have total 95 projects.</p> --}}
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <a href="#" id="slide-modal" class="btn btn-primary">
                                                    <em class="icon ni ni-plus"></em>
                                                    <span>Add Slide</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <input type="hidden" name="slider_id" id="slider_id" value="{{ $slider->id }}">
                                    </div>

                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    @include('flash::message')
                    <div class="nk-block">
                        <div class="row g-gs" id="sortable">
                            @foreach ($slider->slides as $slide)
                                <div class="col-sm-6 col-lg-4 col-xxl-3" id="slide_{{ $slide->id }}" style="cursor: move;">
                                    <div class="card h-100">
                                        @if ($slide->banner == 'default1.png' || $slide->banner == 'default2.png' || $slide->banner == 'default3.png')
                                            <img src="{{ asset('frontend/images/slides/' . $slide->banner) }}" class="card-img-top" alt="" style="height:200px">
                                        @else
                                            <img src="{{ asset('storage/slider/slides/images/' . $slide->banner) }}" class="card-img-top" alt="" style="height:200px">
                                        @endif
                                        <div class="card-inner">
                                            <div class="project">
                                                <div class="project-head">
                                                    <span class="project-title">
                                                        <div class="project-info">
                                                            <h6 class=" mb-2">{{ $slide->name }}</h6>
                                                            @if ($slide->logo == 'default1.png' || $slide->logo == 'default2.png' || $slide->logo == 'default3.png')
                                                                <img src="{{ asset('frontend/images/slides/logo/' . $slide->logo) }}" class="float-right" alt=""
                                                                    style="max-height: 50px">
                                                            @else
                                                                <img src="{{ asset('storage/slider/slides/images/' . $slide->logo) }}" class="float-right" alt=""
                                                                    style="max-height: 50px">
                                                            @endif
                                                        </div>
                                                    </span>
                                                </div>
                                                <div class="project-details">
                                                    <p>{{ $slide->description }}</p>
                                                    @if ($slide->store)
                                                        @if ($slide->store->cashback)
                                                            <p>
                                                                @if ($slide->store->cashback->type == 'fixed')
                                                                    {{ $slide->store->cashback->currency }}
                                                                @endif
                                                                {{ $slide->store->cashback->sale_commission }}@if ($slide->store->cashback->type == 'percentage')
                                                                    %
                                                                @endif Cashback
                                                            </p>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="project-meta">
                                                    <div class="project-progress-task">
                                                        @if ($slide->store)
                                                            <a href="{{ route('admin.stores.show_store') }}?slug={{ $slide->store->slug }}" class="a_link">
                                                                <em class="icon ni ni-cart-fill"></em>
                                                                <span>{{ $slide->store->id }} - {{ $slide->store->name }}</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <div class="float-right">
                                                        <a class="btn btn-primary btn-sm edit-slide" href="{{ route('admin.slides.edit', $slide) }}">
                                                            <em class="icon ni ni-edit"></em>
                                                        </a>
                                                        @if (count($slider->slides) > 1)
                                                            <a class="btn btn-danger btn-sm text-white" onclick="$('#delete-slide-{{ $slide->id }}').submit();"
                                                                style="cursor: pointer">
                                                                <em class="icon ni ni-trash"></em>
                                                            </a>
                                                            <form action="{{ route('admin.slides.destroy', $slide) }}" id="delete-slide-{{ $slide->id }}" method="POST"
                                                                class="m-0">
                                                                @method('DELETE')
                                                                @csrf
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    <x-admin-dashboard.modal modalSize="modal-lg" headerAlignment="align-center" formWrapperClass="" />
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '#slide-modal', function(event) {
                event.preventDefault();
                slider_id = $('#slider_id').val();
                $.ajax({
                    url: "{{ route('admin.slides.create') }}",
                    type: 'GET',
                    data: {
                        slider_id: slider_id
                    },
                    success: function(response) {
                        $('.title').text('Create New Slider');
                        $('#form-wrapper').html(response);
                        $('#save-btn').text('Create');
                        $('#modal').modal('show');
                        initializeSelect2();
                        NioApp.BS.fileinput('.custom-file-input');
                        linkType();
                        storeType();
                        formValidation();
                    }
                });
            });

            $(document).on('click', '.edit-slide', function(event) {
                event.preventDefault();
                pageurl = $(this).attr('href');
                var _token = $("input[name=_token]").val();
                $.ajax({
                    url: pageurl,
                    method: "GET",
                    data: {
                        _token: _token
                    },
                    success: function(response) {
                        $('.title').text('Edit Slider');
                        $('#form-wrapper').html(response);
                        $('#save-btn').text('Edit');
                        NioApp.BS.fileinput('.custom-file-input');
                        $('#modal').modal('show');
                        initializeSelect2();
                        linkType();
                        storeType();
                        formValidation();
                    }
                });
            });
        });

        function initializeSelect2() {
            $('.select-2').select2({
                placeholder: function() {
                    $(this).data('placeholder');
                }
            });
        }
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <script>
        $(function() {
            $("#sortable").sortable({
                update: function(event, ui) {
                    var data = $(this).sortable('serialize');
                    $.ajax({
                        data: data,
                        type: 'POST',
                        url: '{{ route('admin.sort_slides') }}',
                        success: function(data) {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(data.message, data.updated);
                            })(NioApp, jQuery);
                        },
                    });
                }
            });
            $("#sortable").disableSelection();
        });
    </script>
    <script>
        function linkType() {
            $(document).ready(function() {
                if ($('#slider_type').val() == 'link') {
                    $('.sliderlink').show();
                    $('.store').hide();
                    $('#store_id').removeAttr('required').val('');
                } else if ($('#slider_type').val() == 'store') {
                    $('#slider_type').val();
                    $('.store').show();
                    $('.sliderlink').hide();
                     $('#store_id').attr('required', 'required');
                }
            });
        }

        function storeType() {
            $(document.body).on("change", "#slider_type", function() {
                if (this.value == 'store') {
                    $('.store').show();
                    $('.sliderlink').hide();
                    $('#link').removeAttr('required').val('');
                    $("#store_id").val("").trigger('change');
                } else if (this.value == 'link') {
                    $('.sliderlink').show();
                    $('.store').hide();
                    $('#link').attr('required', 'required');
                }

            });
        }
    </script>

    <script>
        function formValidation() {
            let slideId = $('#slide_id').val();
            $('#form-validate').validate({
                errorClass: 'invalid-feedback d-block',
                rules: {
                    name: {
                        required: true
                    },
                    link: {
                        url: true
                    },
                    logo: {
                        extension: "jpg,jpeg,png,bmp",
                    },
                    banner: {
                        required: slideId ? false : true,
                        extension: "jpg,jpeg,png,bmp",
                    },
                },
                messages: {
                    banner: {
                        extension: "only accepted jpg, jpeg, png, bmp images"
                    },
                },
                submitHandler: function(form) {
                    if ($(form).valid())
                        form.submit();
                    return false;
                }
            });
        }
    </script>
@endpush
