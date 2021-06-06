@foreach($childs as $child)
                                    
        <option @if($isEdit) @if($category->id == $child->id) selected @endif  @endif value="{{$child->id}}">{{$dashes}}{{$child->name}}</option>
        @if(count($child->childs))
        @php $newdashes = $dashes.'~'; @endphp
        @if($isEdit)
        @include('admin-dashboard.categories.child_input_picks',['childs' => $child->childs, 'category'=>$category,'dashes'=>$newdashes])
        @else
        @include('admin-dashboard.categories.child_input_picks',['childs' => $child->childs,'dashes'=>$newdashes])
        @endif
        @endif

    @endforeach
