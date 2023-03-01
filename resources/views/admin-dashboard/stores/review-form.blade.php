<form action="{{route('admin.reviews.update',$review)}}" class="gy-3 form-validate is-alter review_form" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="edit_from_store" value="1">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="default-06">Status</label>
                <div class="form-control-wrap ">
                        <select class="form-select form-control" data-search="on" id="default-06" name="status" required>
                            <option @if($review->status == 'active') selected @endif value="active">Active</option>
                            <option @if($review->status == 'pending') selected @endif value="pending">In-active</option>
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