<form action="{{ route(getAdminPrefix() . '.charitiestype-update', $charityType->id) }}" class="" method="POST">
    @method('PUT')
    @csrf
    <div class="row g-4">
        <div class="col-12">
            <div class="form-group">
                <label class="form-label">Title</label>
                <div class="form-control-wrap">
                    <input id="ticket-name" type="text" class="form-control" name="title" value="{{ $charityType->title }}">
                    @error('title')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="form-label" for="default-06">Status</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" id="default-06" name="status" required>
                            <option @if ($charityType->status == 1) selected @endif value="1">Active</option>
                            <option @if ($charityType->status == 0) selected @endif value="0">In-active</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </div>
    </div>
</form>

