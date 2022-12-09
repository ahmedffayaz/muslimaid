@extends('layouts.admin-dashboard.app')
@section('content')
    
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
                                <h4 class="title nk-block-title">Add Store</h4>
                                <div class="nk-block-des">
                                    {{-- <p>You can make style out your....</p> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-inner">
                                <form action="{{route('admin.seo.store')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data"> 
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Url</label>
                                                <div class="form-control-wrap">
                                                    <input id="blog-title" type="text" class="form-control "
                                                        name="url" placeholder="Url" value="{{url('/')}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 ">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Title</label>
                                                <input class="form-control " name="title" placeholder="Title"
                                                    value="" required>
                                            </div>
                                        </div>

                                        {{-- Add Button --}}
                                        <div class="col-lg-1" style="margin-left: 817px;">
                                            <em class="icon ni ni-plus-c append_fields" id="append_fields"></em>
                                        </div>
                                        {{-- End --}}
                                         </div>
                                      
                                        <div class="col-lg-11" id="corsi"></div>
                                       
                                        <div class="col-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-lg btn-primary">Save</button>
                                            </div>
                                        </div>
                                   
                                </form>
                            </div>
                        </div>
                    </div><!-- .nk-block -->
                </div><!-- .components-preview -->
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    var counter = 0;
        $(document).on('click', '#append_fields', function() {

            counter++;
            html = `<div>
                        <div class="form-group">
                            <label class="form-label" for="default-06">Type</label>
                            <div class="form-control-wrap ">
                                <div class="form-control-select">
                                    <select class="form-control rule_type" id="rule_type" data-type_count="${counter}"
                                        name="type[${counter}][rule_type]" required>
                                        <option Selected disabled>Choose type</option>
                                        <option value="meta_keyword">Meta Keyword</option>
                                        <option value="meta_description">Meta Description</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    
                    <div class="col-lg-1" style="margin-left: 817px;">
                        <span>
                            <em class="icon ni ni-minus-circle delBtn" data-type_counter="${counter}"></em>
                        </span>
                    </div>
                    </div>`;
            $('#corsi').append(html);
        });


        $(document).on('change', '.rule_type', function() {
            let val = $(this).val();
            let counter = $(this).attr('data-type_count');
            let html = '';
            $(this).siblings().html('');
            if (val == 'meta_keyword') {
                html = `
                    <div class="form-group">
                        <label class="form-label" for="reviewer">Meta Keywords</label>
                       
                            <input id="blog-title" type="text" class="form-control " name="type[${counter}][value]" placeholder="Meta keyword" value="" required> 
                       
                    </div>`;
            } else {
                html = `
                        <div class="form-group">
                            <label class="form-label" for="reviewer">Meta Description</label>
                            <textarea  class="form-control " name="type[${counter}][value]"  placeholder="Meta Description" value="" required></textarea>
                        </div>`;
            }
            $(this).parent().append(html);
        });

        $(document).on('click', '.delBtn', function() {
            $(this).parent().parent().parent().remove();
        });

    </script>
@endpush