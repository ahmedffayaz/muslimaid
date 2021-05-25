@foreach($childs as $child)
                                    
        <option @if($isEdit) @if($category->parent_id == $child->id) selected @endif @if($category->id == $child->id) disabled @endif @endif value="{{$child->id}}">~{{$child->name}}</option>
        @if(count($child->childs))
        @if($isEdit)
                @include('admin-dashboard.categories.child_input',['childs' => $child->childs, 'category'=>$category])
           @else
           @include('admin-dashboard.categories.child_input',['childs' => $child->childs])
@endif
            @endif

    @endforeach
