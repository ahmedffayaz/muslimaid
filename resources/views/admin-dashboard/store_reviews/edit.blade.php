
<form action="{{route('admin.reviews.update',$review)}}" class="gy-3 form-validate is-alter review_form" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="edit_from_store" value="1">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="reviewer">Reviewer Name</label>
                <div class="form-control-wrap">
                    <input disabled type="text" class="form-control" id="reviewer" name="reviewer" value="{{$review->reviewer}}" required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="default-06">Store</label>
                <div class="form-control-wrap ">
                    
                        <select disabled class="form-select form-control" data-search="on" id="default-06" name="store_id" required>
                            @foreach ($stores as $store)
                            <option @if($store->id == $review->store_id) selected @endif value="{{$store->id}}">{{$store->name}}</option>
                            @endforeach
                        </select>
                    
                </div>
            </div>
        </div>
        
        <div class="col-lg-12">
            <div class="card">
                {{-- <input disabled name="review" type="hidden"> --}}
                <label class="form-label" for="phone-no-1">Review</label>
                <textarea name="review" class="form-control" disabled>{{strip_tags( $review->review )}}</textarea>
                <!-- Create the editor container -->
               
                
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="default-06">Status</label>
                <div class="form-control-wrap ">
                    
                        <select class="form-select form-control" data-search="on" id="default-06" name="status" required>
                            
                            <option @if($review->status == 'active') selected @endif value="active">Active</option>
                            <option @if($review->status == 'pending') selected @endif value="pending">Pending</option>
                            
                        </select>
                    
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
                           