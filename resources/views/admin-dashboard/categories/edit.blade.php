<form action="{{route('admin.categories.update', $category)}}" class="gy-3 form-validate is-alter category_form" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="full-name-1">Category Name</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="full-name-1" name="name" value="{{$category->name}}" required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="default-06">Parent Category</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" id="default-06" name="parent_id" required>
                            
                            
                            <option value="0">None</option>
                            @foreach ($categories as $parent)
                            <option @if($category->parent_id == $parent->id) selected @endif  value="{{$parent->id}}" style="font-weight:bold">{{$parent->name}}</option>
                            @if(count($parent->childs))
                                 @include('admin-dashboard.categories.child_input',['childs' => $parent->childs,'isEdit'=> 1 ,'category'=>$category,'dashes'=>'~'])
                             @endif
                                
                            @endforeach
                                
                           
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <input name="description" type="hidden">
                <label class="form-label" for="phone-no-1">Description</label>
                <!-- Create the editor container -->
                <div  id="editor-container">
                {!!$category->description!!}
                </div>
               
            </div>
        </div>
        <div class="col-lg-12 ">

        <label class="form-label">Logo/Icon</label><br>
        @if($category->logo_type == 'upload')
        
        <img src="{{asset('storage/categories/images/'.$category->logo_upload)}}" style="max-height: 60px;max-width:60px" alt="">
      
        @elseif($category->logo_type == 'link')
        
        <img src="{{$category->logo_link}}" style="max-height: 60px;max-width:60px" alt="">
     
        @endif
        </div>
       
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="logo_type">Logo/Icon Type</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" name="logo_type" id='logo_type' required>
                            
                            <option @if($category->logo_type == 'upload') selected @endif value="upload">Upload</option>
                            <option @if($category->logo_type == 'link') selected @endif value="link">Link</option>
                                
                           
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 logo_link">
            <div class="form-group">
                <label class="form-label" for="logo_link">Logo/Icon Link</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="logo_link" name="logo_link" value="{{$category->logo_link}}">
                </div>
            </div>
        </div>
        <div class="col-lg-6 logo_upload">
            <div class="form-group">
                <label class="form-label" for="logo_upload">Logo/Icon Upload</label>
                <div class="form-control-wrap">
                    <div class="custom-file">
                        <input type="file" class="" name='logo_upload' id="logo_upload">
                        {{-- <label class="custom-file-label" for="logo_upload">Choose file</label> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 d-none">

        <label class="form-label">Banner</label><br>
        
        @if($category->banner_type == 'upload')
       
            <img src="{{asset('storage/categories/images/'.$category->banner_upload)}}" style="max-height: 150px" alt="">
        
        @elseif($category->banner_type == 'link')
        
            <img src="{{$category->banner_link}}" style="max-height: 150px" alt="">
      
        @endif
        </div>
        
        <div class="col-lg-6 d-none">
            <div class="form-group">
                <label class="form-label" for="banner_type">Banner Type</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" name="banner_type" id='banner_type' required>
                            <option @if($category->banner_type == 'upload') selected @endif value="upload">Upload</option>
                            <option @if($category->banner_type == 'link') selected @endif value="link">Link</option>
                            
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 banner_link d-none">
            <div class="form-group">
                <label class="form-label" for="banner_link">Banner Link</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="banner_link"  value="{{$category->banner_link}}" name="banner_link">
                </div>
            </div>
        </div>
        <div class="col-lg-6 banner_upload d-none">
            <div class="form-group">
                <label class="form-label" for="banner_upload">Banner Upload</label>
                <div class="form-control-wrap">
                    <div class="custom-file">
                        <input type="file" class="" name="banner_upload" id="banner_upload">
                        {{-- <label class="custom-file-label" for="banner_upload">Choose file</label> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="full-name-1">Sort</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="full-name-1" name="sort" value="{{$category->sort}}">
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="default-06">Status</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" id="default-06" name="status" required>
                            
                            <option @if($category->status == '1') selected @endif value="1">Active</option>
                            <option @if($category->status == '0') selected @endif value="0">In-active</option>
                                
                           
                        </select>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-12">
            <label class="form-label" for="default-06">Editor Picks</label>
                <div class="form-control-wrap ">
                    <div class="">
                        <select class="form-control form-select select-2" name="picks[]" id="picks" required multiple>

                            @foreach ($stores as $store)
                              <option  @if(in_array($store->id, $category->picks->pluck('store_id')->toArray())) selected  @endif value="{{$store->id}}">{{$store->id}} - {{$store->name}}</option>
                            @endforeach
                          
                        </select>
                    </div>
                </div> 
        </div> --}}
        @if($category->parent_id == 0)
        <div class="col-md-12">
            <label class="form-label" for="default-06">Tags</label>
                <div class="form-control-wrap ">
                    <div class="">
                        <select class="form-control form-select select-2" name="tags[]" id="tags" multiple>
                            <option @if($category->feature_homepage) selected @endif value="feature_homepage">Homepage featured</option>
                            <option @if($category->feature_sidebar) selected @endif value="feature_sidebar">Sidebar featured</option>
                        </select>
                    </div>
                </div> 
        </div>
        
        @endif
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-primary">Save</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        if ($('#logo_type').val() == 'upload') {
            $('.logo_upload').show();
            $('.logo_link').hide();

        }
        else if ($('#logo_type').val() == 'link') {
            $('.logo_link').show();
            $('.logo_upload').hide();
        }
        
    });
    $(document.body).on("change","#logo_type",function(){
        if (this.value == 'upload') {
            $('.logo_upload').show();
            $('.logo_link').hide();
            $('#logo_link').removeAttr('required').val('');

        }
        else if (this.value == 'link') {
           
            $('.logo_link').show();
            $('#logo_link').attr('required', 'required');
            $('.logo_upload').hide();
        }
        
    });
</script>
<script>
    $(document).ready(function() {
        if ($('#banner_type').val() == 'upload') {
            $('.banner_upload').show();
            $('.banner_link').hide();
            $('#banner_link').removeAttr('required').val('');

        }
        else if ($('#banner_type').val() == 'link') {
           
            $('.banner_link').show();
            $('#banner_link').attr('required', 'required');
            $('.banner_upload').hide();
        }
        
    });
    $(document.body).on("change","#banner_type",function(){
      
        if (this.value == 'upload') {
            $('.banner_upload').show();
            $('.banner_link').hide();
            $('#banner_link').removeAttr('required').val('');

        }
        else if (this.value == 'link') {
           
            $('.banner_link').show();
            $('#banner_link').attr('required', 'required');
            $('.banner_upload').hide();
        }
        
    });
</script>