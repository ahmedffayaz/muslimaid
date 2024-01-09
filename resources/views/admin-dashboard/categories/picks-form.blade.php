<form action="{{ route(getAdminPrefix() . '.stores.create_editor_picks') }}" class="gy-3 form-validate is-alter"
    method="POST" enctype="multipart/form-data" id="category-picks-form">
    @csrf
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="default-06">Category</label>
                <div class="form-control-wrap ">
                    <div class="">
                        <select class="form-control select-2" id="default-06" name="category_id" required>
                            @foreach ($categories as $cat)
                                <option @if ($category->id == $cat->id) selected @endif value="{{ $category->id }}"
                                    style="font-weight:bold">{{ $category->name }}</option>
                                @if (count($cat->childs))
                                    @include('admin-dashboard.categories.child_input_picks', [
                                        'childs' => $cat->childs,
                                        'isEdit' => 1,
                                        'category' => $category,
                                        'dashes' => '~',
                                    ])
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <label class="form-label" for="default-06">Editor Picks</label>
            <div class="form-control-wrap ">
                <div class="">
                    <select class="form-control form-select select-2" name="picks[]" id="picks" required multiple>
                        @foreach ($stores as $store)
                            <option @if (in_array($store->id, $category->picks->pluck('store_id')->toArray())) selected @endif value="{{ $store->id }}">
                                {{ $store->id }} - {{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-primary" id="save-btn"></button>
            </div>
        </div>
    </div>
</form>
