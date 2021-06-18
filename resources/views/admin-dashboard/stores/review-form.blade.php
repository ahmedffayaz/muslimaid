<form action="{{route('admin.reviews.update',$review)}}" class="gy-3 form-validate is-alter review_form" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="edit_from_store" value="1">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="reviewer">Reviewer Name</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="reviewer" name="reviewer" value="{{$review->reviewer}}" required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="rating">Rating</label>
                <div class="form-control-wrap ">
                    
                        <select class="form-select form-control"  id="rating" name="rating" required>
                            
                            <option @if($review->rating == 1) selected @endif value="1">1</option>
                            <option @if($review->rating == 2) selected @endif value="2">2</option>
                            <option @if($review->rating == 3) selected @endif value="3">3</option>
                            <option @if($review->rating == 4) selected @endif value="4">4</option>
                            <option @if($review->rating == 5) selected @endif value="5">5</option>
                            
                        </select>
                    
                </div>
            </div>
        </div>
        
        
        <div class="col-lg-12">
            <div class="card">
                <input name="review" type="hidden">
                <label class="form-label" for="phone-no-1">Review</label>
                <!-- Create the editor container -->
                <div  id="reditor-container">
                    {!! $review->review!!}
                </div>
                
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="default-06">Status</label>
                <div class="form-control-wrap ">
                    
                        <select class="form-select form-control" data-search="on" id="default-06" name="status" required>
                            
                            <option @if($review->status == 'active') selected @endif value="active">Active</option>
                            <option @if($review->status == 'in-active') selected @endif value="in-active">In-active</option>
                            
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