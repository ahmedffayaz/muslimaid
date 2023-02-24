@extends('layouts.admin-dashboard.app')
@section('content')
    @php
        $isEdit = isset($seoData) ? true : false;
         $url = $isEdit ? route('admin.seo.update', $seoData) : route('admin.seo.store');
    @endphp
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
                                    <h4 class="title nk-block-title">{{$isEdit ? 'Edit Seo' : 'Add Seo'}}</h4>
                                    <div class="nk-block-des">
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-inner">
                                    <form action="{{ $url }}" class="form-validate is-alter"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @if($isEdit)
                                        @method('put')
                                        @endif
                                        <div class="row g-4">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Url <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input id="blog-title" type="text" class="form-control "
                                                            name="url" placeholder="Url" value="{{ $isEdit ? $seoData->url :  url('/') }}"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 ">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Title <span class="text-danger">*</span></label>
                                                    <input class="form-control " name="title" placeholder="Title"
                                                        value="{{ $isEdit ? $seoData->title : ''}}" required>
                                                </div>
                                            </div>

                                            {{-- Add Button --}}
                                            <div class="col-lg-1" style="margin-left: 817px; color: green;">
                                                <em class="icon ni ni-plus-c append_fields" id="append_fields"></em>
                                            </div>
                                            {{-- End --}}
                                        </div>
                                        <div class="seo-fields"  data-count="{{$isEdit ? $seoData->ruleData()->get()!=null ? $seoData->ruleData()->count() : 1  : 1}}">
                                            @if($isEdit && $seoData)
                                            @foreach ($seoData->ruleData()->get() as $key => $val)
                                                    <div class="col-lg-11">
                                                        <div class="form-group">
                                                            <label class="form-label" for="reviewer">Meta Keywords</label>

                                                                <input id="blog-title" type="text" class="form-control " name="value[{{$key}}][keyword]" placeholder="Meta keyword" value="{{ $val->meta_keyword }}" required>

                                                        </div>
                                                        <div class="form-group">
                                                                <label class="form-label" for="reviewer">Meta Description</label>
                                                                <textarea  class="form-control " name="value[{{$key}}][meta_description]"  placeholder="Meta Description" value="" required>{{ $val->meta_description }}</textarea>
                                                        </div>

                                                        <div class="col-lg-1" style="margin-left: 817px;color: red;">
                                                            <span>
                                                                <em class="icon ni ni-minus-circle delBtn" data-type_counter="${counter}"></em>
                                                            </span>
                                                        </div>
                                                        </div>
                                                    @endforeach
                                                    @endif
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
            let counter = $('.seo-fields').attr('data-count');
           // alert(counter);

            html = `<div>
            <div class="form-group">
                        <label class="form-label" for="reviewer">Meta Keywords</label>

                            <input id="blog-title" type="text" class="form-control " name="value[${counter}][keyword]" placeholder="Meta keyword" value="" required>

                    </div>
                    <div class="form-group">
                            <label class="form-label" for="reviewer">Meta Description</label>
                            <textarea  class="form-control " name="value[${counter}][meta_description]"  placeholder="Meta Description" value="" required></textarea>
                    </div>

                    <div class="col-lg-1" style="margin-left: 817px;">
                        <span>
                            <em class="icon ni ni-minus-circle delBtn" data-type_counter="${counter}"></em>
                        </span>
                    </div>
                    </div>`;
                    counter++;
                  $('#corsi').append(html);
                  $('.seo-fields').attr('data-count', counter);
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
