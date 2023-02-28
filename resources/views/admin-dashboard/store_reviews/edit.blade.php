<form action="{{ route('admin.reviews.update', $review) }}" class="gy-3 form-validate is-alter review_form" method="POST" id="store-reviews-form">
    @csrf
    @method('PUT')
    <input type="hidden" name="edit_from_store" value="1">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="reviewer">Reviewer <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <select disabled class="form-select form-control" data-search="on" name="user_id" required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $user->id == $review->user_id ? 'selected' : '' }}>{{ $user->first_name . ' ' . $user->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="default-06">Store <span class="text-danger">*</span></label>
                <div class="form-control-wrap ">
                    <select disabled class="form-select form-control" data-search="on" id="default-06" name="store_id" required>
                        @foreach ($stores as $store)
                            <option @if ($store->id == $review->store_id) selected @endif value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <label class="form-label" for="phone-no-1">Review <span class="text-danger">*</span></label>
                <textarea name="review" class="form-control" disabled>{{ strip_tags($review->review) }}</textarea>

            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="default-06">Status</label>
                <div class="form-control-wrap ">

                    <select class="form-select form-control" data-search="on" id="default-06" name="status" required>

                        <option @if ($review->status == 'active') selected @endif value="active">Active</option>
                        <option @if ($review->status == 'pending') selected @endif value="pending">Pending</option>

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
