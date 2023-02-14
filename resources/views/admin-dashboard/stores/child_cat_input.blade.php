@foreach ($childs as $child)
    <ul class="categories">
        <li class="custom-control custom-control-sm custom-checkbox d-block">
            <input @if (in_array($child->id, $store->categories->pluck('id')->toArray())) checked @endif class="custom-control-input" type="checkbox" name="category_id[]" id="{{ $child->name }}_{{ $child->id }}"
                value="{{ $child->id }}">
            <label class="custom-control-label" for="{{ $child->name }}_{{ $child->id }}">{{ $child->name }}</label>
            @if (count($child->childs))
                @include('admin-dashboard.stores.child_cat_input', ['childs' => $child->childs])
            @endif
        </li>
    </ul>
@endforeach
