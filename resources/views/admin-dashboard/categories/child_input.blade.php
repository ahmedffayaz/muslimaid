@foreach ($childs as $child)
    <option @if ($isEdit) 
                @if ($category->parent_id == $child->id) selected @endif
                @if ($category->id == $child->id) disabled @endif 
            @endif
        value="{{ $child->id }}">{{ $dashes }}{{ $child->name }}</option>
    @if (count($child->childs))
        @php $newdashes = $dashes.'~'; @endphp
        @if ($isEdit)
            @include('admin-dashboard.categories.child_input', [
                'childs' => $child->childs,
                'category' => $category,
                'dashes' => $newdashes,
            ])
        @else
            @include('admin-dashboard.categories.child_input', [
                'childs' => $child->childs,
                'dashes' => $newdashes,
            ])
        @endif
    @endif
@endforeach
