

<form action="{{route('admin.importedcategories.update',$importedcategory)}}" class="gy-3 form-validate is-alter" method="POST">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="full-name-1">Category Name</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="full-name-1" name="name" required value="{{$importedcategory->name}}" disabled>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="default-06">Map to</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" id="default-06" name="site_category_id" required>
                            <option value="0"> Unmapped</option>
                            @foreach ($site_categories as $site_category)
                            <option value="{{$site_category->id}}" @if($importedcategory->mapped_to == $site_category->id) selected @endif >
                                 {{$site_category->name}}</option>
                                 @if(count($site_category->childs))
                                 @include('admin-dashboard.imported-categories.child_input',['childs' => $site_category->childs,'isEdit'=> 1 ,'category'=>$importedcategory,'dashes'=>'~'])
                             @endif
                                
                            @endforeach
                        </select>
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