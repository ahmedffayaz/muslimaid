@extends('layouts.admin-dashboard.app')
@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Sliders</h3>
                                <div class="nk-block-des text-soft">
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="row g-gs ">
                            @foreach ($sliders as $slider)
                                <div class="col-sm-6 col-lg-4 col-xxl-3">
                                    <div class="card h-100">
                                        <div class="card-inner">
                                            <div class="project">
                                                <div class="project-head">
                                                    <a href="{{ route('admin.sliders.edit', $slider) }}" class="project-title">
                                                        <div class="project-info">
                                                            <h6 class="title">{{ $slider->name }}</h6>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="project-meta">
                                                    <div class="project-progress-task"><em class="icon ni ni-check-round-cut"></em><span>{{ $slider->slides->count() }}
                                                            Slides</span></div>
                                                    <a class="btn btn-primary btn-sm" href="{{ route('admin.sliders.edit', $slider) }}"><em class="icon ni ni-edit"></em><span>Edit
                                                            Slider</span></a>
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
    <!-- Modal Form -->
    <div class="modal fade" tabindex="-1" id="modalForm">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create slider</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <form action="{{ route('admin.sliders.store') }}" class="form-validate is-alter" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label" for="title">Slider Name <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="title" name="name" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id='add-btn'>Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
