<form action="{{route('admin.slides.update',$slide)}}" class="gy-3 form-validate is-alter category_form" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="full-name-1">Slide name</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="full-name-1" name="name" value="{{$slide->name}}" required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="default-06">Store</label>
                <div class="form-control-wrap ">
                    <div class="">
                        <select class="form-select select-2" id="default-06" name="store_id" required data-search="on">
                            
                            @foreach ($stores as $store)
                            <option @if($slide->store_id == $store->id) selected @endif value="{{$store->id}}">{{$store->id}} - {{$store->name}}</option>
                                
                            @endforeach
                                
                           
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <label class="form-label" for="phone-no-1">Description</label>
                <textarea name="description" class="form-control" rows="5" required>{{$slide->description}}</textarea>
                                               
            </div>
        </div>
        
        <div class="col-lg-6 logo_upload">
            <div class="form-group">
                <label class="form-label" for="logo">Logo</label>
                <div class="form-control-wrap">
                    <div class="custom-file">
                        <input type="file" class="" name='logo' id="logo">
                        {{-- <label class="custom-file-label" for="logo">Choose file</label> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 banner_upload">
            <div class="form-group">
                <label class="form-label" for="banner">Banner</label>
                <div class="form-control-wrap">
                    <div class="custom-file">
                        <input type="file" class="" name="banner" id="banner" >
                        {{-- <label class="custom-file-label" for="banner">Choose file</label> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-primary">Save</button>
            </div>
        </div>
    </div>
</form>