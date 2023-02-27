@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview mx-auto">
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <div class="nk-block-des">
                                </div>
                            </div>
                        </div>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                          <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                          </ul>
                        </div>
                        @endif
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    {{-- <h5 class="card-title">Blog</h5> --}}
                                </div>
                                <form action="{{route('admin.seo.update',$seo)}}" class="form-validate is-alter" method="POST" >
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Url <span class="text-danger">*</span></label>
                                                <div class="form-control-wrap">
                                                    <input id="blog-title" type="text" class="form-control " name="url" placeholder="Url" value="{{ $seo->url }}" readonly> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                            <label class="form-label" for="reviewer">Title <span class="text-danger">*</span></label>
                                            <input  class="form-control " name="title" placeholder="Title" value="{{ $seo->title }}" required>
                                        </div>
                                        </div>
                                        
                                        
                                        <div class="col-lg-11">
                                            <div class="rule-type-container remove" data-count="{{ $seo->ruleData->count() }}">
                                                @foreach ($seo->ruleData as $key => $rule_data)
                                                    <div class="form-group">
                                                        <label class="form-label" for="default-06">Type <span class="text-danger">*</span></label>
                                                        <div class="form-control-wrap ">
                                                            <div class="form-control-select">
                                                                <select class="form-control rule_type" data-type_count="{{$key}}" id="rule_type" name="type[{{$key}}][rule_type]" required>
                                                                    <option Selected disabled>Choose type</option>
                                                                    <option value="meta_keyword" {{$rule_data->type == 'meta_keyword' ? 'selected' : ''}}>Meta Keyword</option>
                                                                    <option value="meta_description" {{$rule_data->type == 'meta_description' ? 'selected' : ''}}>Meta Description</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="remove">
                                                        @if ($rule_data->type == 'meta_keyword')
                                                            <div class="form-group">
                                                                <label class="form-label" for="reviewer">Meta Keywords</label>
                                                                <div class="form-control-wrap">
                                                                    <input id="blog-title" type="text" class="form-control " name="type[{{$key}}][value]" placeholder="Meta keyword" value="{{$rule_data->value}}" required> 
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="form-group">
                                                                <label class="form-label" for="reviewer">Meta Description</label>
                                                                <textarea  class="form-control " name="type[{{$key}}][value]"  placeholder="Meta Description" required>{{$rule_data->value}}</textarea>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <span>
                                                        <em class="icon ni ni-minus-circle removebtn" data-type_counter="{{$key}}" ></em>
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-lg-1 mt-3">
                                            <span><em class="icon ni ni-plus-circle" onclick="appendTypeField();"></em></span>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <button onclock="remobe_bug()" class="btn btn-primary add-blog" type="submit">Save</button>
                                                
                                            </div>
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
 function appendTypeField( )
{   
    var counter = Number($('.rule-type-container').attr('data-count'));
    counter++;
    let html = `<div>
    <div class="form-group">
                  <label class="form-label" for="default-06">Type</label>
                    <div class="form-control-wrap ">
                      <div class="form-control-select">
                        <select class="form-control rule_type" id="rule_type" data-type_count="${counter}" name="type[${counter}][rule_type]"  required>
                            <option Selected disabled>Choose type</option>
                            <option value="meta_keyword">Meta Keyword</option>
                            <option value="meta_description">Meta Description</option>
                        </select>   
                    </div>
                </div>
                    <span><em class="icon ni ni-minus-circle removebtn" data-type_counter="${counter}" onclick="removeFields();"></em></span>
                    </div>`;
       // counter++;
        $('.rule-type-container').append(html);
        $('.rule-type-container').attr('data-count', counter);
        
}

$(document).on('change', '.rule_type', function() {
    let val = $(this).val();
    let counter = $(this).attr('data-type_count');
    let html = '';
    //$(this).parents('.form-group').siblings('.remove').html(''));
    $(this).siblings().html('');
    if (val == 'meta_keyword') {
         html = `  <div class="remove" >
                        <div class="form-group">
                            <label class="form-label" for="reviewer">Meta Keywords</label>
                            <div class="form-control-wrap">
                                <input id="blog-title" type="text" class="form-control " name="type[${counter}][value]" placeholder="Meta keyword" value="" required> 
                            </div>
                        </div>
                    </div>`;
        
    } else {
         html = `<div class="remove">
                        <div class="form-group">
                            <label class="form-label" for="reviewer">Meta Description</label>
                            <textarea  class="form-control " name="type[${counter}][value]"  placeholder="Meta Description" value="" required></textarea>
                        </div>
                    </div>
                </div>`;
    }   
    $(this).parent().append(html);
});

$(document).on('click', '.removebtn', function() {

    $(this).parent().siblings('.remove').remove();
 
});
</script>
@endpush