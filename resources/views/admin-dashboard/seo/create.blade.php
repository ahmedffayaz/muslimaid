@extends('layouts.admin-dashboard.app')
@section('content')
<Style>
    .g-4{
        margin: -0.75rem !important;
         padding: 10px !important;
    }
    #btn-setting{
        padding-top: 47px;
    }
    
</style>    
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview mx-auto">
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                {{-- <h4 class="title nk-block-title">Create Blog</h4> --}}
                                <div class="nk-block-des">
                                    {{-- <p>You can make style out your....</p> --}}
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
                                <form action="{{route('admin.seo.store')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6  g-4">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Url</label>
                                                <div class="form-control-wrap">
                                                    <input id="blog-title" type="text" class="form-control " name="url" placeholder="Url" value="" required> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6  g-4">
                                            <div class="form-group">
                                            <label class="form-label" for="reviewer">Title</label>
                                            <input  class="form-control " name="title" placeholder="Title" value="" required>
                                        </div>
                                        </div>
                                  
                                        <div class="col-lg-11  g-4">
                                            <div class="rule-type-container" data-count="0">
                                                <div class="form-group">
                                                    <label class="form-label" for="default-06">Type</label>
                                                    <div class="form-control-wrap ">
                                                        <div class="form-control-select">
                                                            <select class="form-control rule_type" data-type_count="0" id="rule_type" name="type[0][rule_type]" required>
                                                                <option Selected disabled>Choose type</option>
                                                                <option value="meta_keyword">Meta Keyword</option>
                                                                <option value="meta_description">Meta Description</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1 cr" id="btn-setting">
                                            <span><em class="icon ni ni-plus-circle" onclick="appendTypeField();"></em></span>
                                        </div>
                                    
                                        <div class="col-12  g-4">
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
    let html = `
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
                <span>
                    <em class="icon ni ni-minus-circle removebtn" data-type_counter="${counter}"></em>
                </span>
            `;
       // counter++;
        $('.rule-type-container').append(html);
        $('.rule-type-container').attr('data-count', counter);
        
}

$(document).on('change', '.rule_type', function() {
    let val = $(this).val();
    let counter = $(this).attr('data-type_count');
    let html = '';
    $(this).siblings().html('');
    if (val == 'meta_keyword') {
         html = `<div class="col-lg-12" >
                <div class="form-group">
                    <label class="form-label" for="reviewer">Meta Keywords</label>
                    <div class="form-control-wrap">
                        <input id="blog-title" type="text" class="form-control " name="type[${counter}][value]" placeholder="Meta keyword" value="" required> 
                    </div>
                </div>
            </div>`;  
    } else {
         html = `<div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-label" for="reviewer">Meta Description</label>
                            <textarea  class="form-control " name="type[${counter}][value]"  placeholder="Meta Description" value="" required></textarea>
                        </div>
                    </div>`;
    }   
    $(this).parent().append(html);
});


$(document).on('click', '.removebtn', function() {
   
    $(this).parents('.form-group').remove();
  
});

</script>
@endpush